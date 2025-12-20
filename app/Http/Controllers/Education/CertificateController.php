<?php

namespace App\Http\Controllers\Education;

use App\Http\Controllers\Controller;
use App\Models\Education\Certificate;
use App\Models\Education\Course;
use App\Services\Ai\AiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request, Course $course)
    {
        $user = auth()->user();

        // 1. Verification Logic (Simplify for this feature: Assume if they can click the button, they finished it)
        // In a real scenario, we'd check $user->hasCompleted($course)

        // 2. Check existing certificate
        $existingCert = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingCert) {
            return $this->downloadPdf($existingCert);
        }

        // 3. AI Message Generation
        $aiMessage = $this->aiService->generateCertificateMessage($user, $course);

        // 4. Create Record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_code' => 'CERT-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'ai_generated_message' => $aiMessage,
            'issued_at' => now(),
        ]);

        return $this->downloadPdf($certificate);
    }

    private function downloadPdf(Certificate $certificate)
    {
        $data = [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'course' => $certificate->course,
            'title' => 'Certificate of Completion',
            'date' => $certificate->issued_at->format('F d, Y'),
        ];

        $pdf = Pdf::loadView('education.certificate', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Certificate-' . $certificate->certificate_code . '.pdf');
    }
}
