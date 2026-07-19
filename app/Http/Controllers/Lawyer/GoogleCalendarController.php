<?php

/*
 * Google Cloud Setup (one-time):
 * 1. Go to console.cloud.google.com and create a project named "Ain Sheba"
 * 2. Enable "Google Calendar API" under APIs & Services → Library
 * 3. Go to APIs & Services → Credentials → Create OAuth 2.0 Client ID
 *    - Application type: Web application
 *    - Authorized redirect URI: http://localhost:8000/lawyer/google/callback
 * 4. Download the credentials JSON; rename to google-credentials.json; place in storage/app/
 * 5. Copy these values into .env:
 *    GOOGLE_CLIENT_ID=your_client_id
 *    GOOGLE_CLIENT_SECRET=your_client_secret
 *    GOOGLE_REDIRECT_URI=http://localhost:8000/lawyer/google/callback
 */

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GoogleCalendarController extends Controller
{
    // Redirect lawyer to Google OAuth consent screen
    public function redirectToGoogle()
    {
        $params = [
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope'         => 'https://www.googleapis.com/auth/calendar.events',
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ];

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        return redirect($url);
    }

    // Handle the OAuth callback and store tokens
    public function handleCallback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect()->route('lawyer.dashboard')
                ->with('error', 'Google OAuth failed — no authorisation code returned.');
        }

        // Exchange authorisation code for access + refresh tokens using Guzzle
        $client   = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'client_id'     => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
                'code'          => $code,
                'grant_type'    => 'authorization_code',
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);

        auth()->user()->update([
            'google_access_token'     => $tokenData['access_token'],
            'google_refresh_token'    => $tokenData['refresh_token'] ?? null,
            'google_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
        ]);

        return redirect()->route('lawyer.dashboard')
            ->with('success', 'Google Calendar connected successfully!');
    }

    // Clear stored tokens to disconnect Calendar
    public function disconnect()
    {
        auth()->user()->update([
            'google_access_token'     => null,
            'google_refresh_token'    => null,
            'google_token_expires_at' => null,
        ]);

        return redirect()->back()->with('success', 'Google Calendar disconnected.');
    }

    // JSON endpoint — called via AJAX from the lawyer dashboard (Lab 12 pattern)
    public function getUpcomingEvents()
    {
        $user = auth()->user();

        if (!$user->google_access_token) {
            return response()->json(['error' => 'Google Calendar not connected.'], 401);
        }

        // Silently refresh the access token if it has expired
        if (now() >= $user->google_token_expires_at) {
            $this->refreshAccessToken($user);
            $user->refresh();
        }

        // Call the Google Calendar API using Guzzle — same pattern as lab getWeatherJson()
        $client   = new Client();
        $response = $client->get('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
            'headers' => ['Authorization' => 'Bearer ' . $user->google_access_token],
            'query'   => [
                'timeMin'      => now()->toRfc3339String(),
                'maxResults'   => 10,
                'singleEvents' => 'true',
                'orderBy'      => 'startTime',
                'q'            => 'Ain Sheba Consultation',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return response()->json(['events' => $data['items'] ?? []]);
    }

    // Refresh the stored access token using the refresh token
    private function refreshAccessToken($user): void
    {
        $client   = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'client_id'     => env('GOOGLE_CLIENT_ID'),
                'client_secret' => env('GOOGLE_CLIENT_SECRET'),
                'refresh_token' => $user->google_refresh_token,
                'grant_type'    => 'refresh_token',
            ],
        ]);

        $tokenData = json_decode($response->getBody(), true);

        $user->update([
            'google_access_token'     => $tokenData['access_token'],
            'google_token_expires_at' => now()->addSeconds($tokenData['expires_in']),
        ]);
    }
}
