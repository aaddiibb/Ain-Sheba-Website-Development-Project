<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function show(string $code)
    {
        $certificate = Certificate::with(['citizen', 'program.lawyer'])
            ->where('certificate_code', $code)
            ->firstOrFail();

        if (auth()->id() !== $certificate->citizen_id) {
            abort(403);
        }

        return view('citizen.certificates.show', compact('certificate'));
    }

    public function download(string $code)
    {
        $certificate = Certificate::with(['citizen', 'program.lawyer'])
            ->where('certificate_code', $code)
            ->firstOrFail();

        if (auth()->id() !== $certificate->citizen_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('citizen.certificates.pdf', compact('certificate'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('AinSheba-Certificate-' . $code . '.pdf');
    }
}
