<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultation::with(['citizen', 'lawyer']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $consultations = $query->latest()->paginate(15)->withQueryString();

        return view('admin.consultations.index', compact('consultations'));
    }
}
