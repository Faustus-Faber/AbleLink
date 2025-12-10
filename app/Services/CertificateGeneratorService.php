<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Certificate Generator Service
 * 
 * Generates accessible PDF certificates with QR code verification.
 */
class CertificateGeneratorService
{
    /**
     * Generate a certificate for a user who completed a course.
     */
    public function generateCertificate(User $user, Course $course): ?Certificate
    {
        // Check if user has completed all lessons
        if (!$this->hasCompletedCourse($user, $course)) {
            return null;
        }

        // Check if certificate already exists
        $existingCertificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingCertificate) {
            return $existingCertificate;
        }

        // Generate certificate number
        $certificateNumber = $this->generateCertificateNumber($user, $course);

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
            'is_verified' => true,
        ]);

        // Generate QR code and PDF
        $this->generateQrCode($certificate);
        $this->generatePdf($certificate);

        return $certificate;
    }

    /**
     * Check if user has completed all lessons in the course.
     */
    protected function hasCompletedCourse(User $user, Course $course): bool
    {
        $enrollment = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment || !$enrollment->completed_at) {
            return false;
        }

        $allLessons = $course->lessons()->where('is_active', true)->pluck('id')->toArray();
        $completedLessons = $enrollment->completed_lessons ?? [];

        return count($allLessons) === count($completedLessons) && 
               count(array_intersect($allLessons, $completedLessons)) === count($allLessons);
    }

    /**
     * Generate a unique certificate number.
     */
    protected function generateCertificateNumber(User $user, Course $course): string
    {
        $prefix = 'ALC-' . strtoupper(substr($course->title, 0, 3));
        $timestamp = now()->format('Ymd');
        $random = Str::upper(Str::random(6));
        
        return $prefix . '-' . $timestamp . '-' . $random;
    }

    /**
     * Generate QR code for certificate verification.
     */
    protected function generateQrCode(Certificate $certificate): void
    {
        $verificationUrl = route('certificates.verify', ['number' => $certificate->certificate_number]);
        
        $qrCodePath = 'certificates/qr/' . $certificate->id . '.png';
        $fullPath = storage_path('app/public/' . $qrCodePath);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // Generate QR code (using a simple approach - can integrate QR code library)
        // For now, we'll create a placeholder
        $certificate->update(['qr_code_path' => $qrCodePath]);
    }

    /**
     * Generate accessible PDF certificate.
     */
    protected function generatePdf(Certificate $certificate): void
    {
        $pdfPath = 'certificates/pdf/' . $certificate->id . '.pdf';
        $fullPath = storage_path('app/public/' . $pdfPath);

        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        // For now, create a placeholder
        // In production, use a PDF library like DomPDF or TCPDF with proper accessibility tags
        $certificate->update(['pdf_path' => $pdfPath]);
    }

    /**
     * Verify a certificate by number.
     */
    public function verifyCertificate(string $certificateNumber): ?Certificate
    {
        return Certificate::where('certificate_number', $certificateNumber)
            ->where('is_verified', true)
            ->with(['user', 'course'])
            ->first();
    }
}





