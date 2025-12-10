<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificateGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    protected $certificateGenerator;

    public function __construct(CertificateGeneratorService $certificateGenerator)
    {
        $this->certificateGenerator = $certificateGenerator;
    }

    /**
     * Show user's certificates.
     */
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())
            ->with('course')
            ->latest('issued_at')
            ->paginate(12);

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show certificate details.
     */
    public function show(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        $certificate->load('course', 'user');

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Verify a certificate by number (public).
     */
    public function verify(Request $request, string $number)
    {
        $certificate = $this->certificateGenerator->verifyCertificate($number);

        return view('certificates.verify', compact('certificate', 'number'));
    }

    /**
     * Download certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$certificate->pdf_path || !file_exists(storage_path('app/public/' . $certificate->pdf_path))) {
            abort(404, 'Certificate PDF not found.');
        }

        return response()->download(storage_path('app/public/' . $certificate->pdf_path));
    }
}

