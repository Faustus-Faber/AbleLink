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
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request, Course $course): \Symfony\Component\HttpFoundation\Response
    {
        $user = auth()->user();
        
        if ($user === null) {
            abort(401, 'User not authenticated');
        }

        $certQuery = Certificate::where('user_id', $user->id);
        $certQuery->where('course_id', $course->id);
        $existingCert = $certQuery->first();

        if ($existingCert !== null) {
            return $this->downloadPdf($existingCert);
        }

        $aiMessage = $this->aiService->generateCertificateMessage($user, $course);

        $certificateParams = [
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_code' => 'CERT-' . date('Y') . '-' . strtoupper(Str::random(8)),
            'ai_generated_message' => $aiMessage,
            'issued_at' => now(),
        ];
        
        $certificate = Certificate::create($certificateParams);

        return $this->downloadPdf($certificate);
    }

    private function downloadPdf(Certificate $certificate): \Symfony\Component\HttpFoundation\Response
    {
        $userData = $certificate->user;
        $courseData = $certificate->course;
        
        $formattedDate = '';
        if ($certificate->issued_at !== null) {
            $formattedDate = $certificate->issued_at->format('F d, Y');
        }
        
        $data = [
            'certificate' => $certificate,
            'user' => $userData,
            'course' => $courseData,
            'title' => 'Certificate of Completion',
            'date' => $formattedDate,
        ];

        $pdf = Pdf::loadView('education.certificate', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Certificate-' . $certificate->certificate_code . '.pdf');
    }
}
