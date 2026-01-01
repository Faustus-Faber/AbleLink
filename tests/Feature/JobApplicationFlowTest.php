<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

// F9 - Evan Yuvraj Munshi
class JobApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_apply_for_job_with_cv()
    {
        Storage::fake('public');
        $candidate = User::factory()->create(['role' => 'candidate']);
        $employer = User::factory()->create(['role' => 'employer']);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Laravel Developer needed',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        $file = UploadedFile::fake()->create('cv.pdf', 100);

        $response = $this->actingAs($candidate)
            ->post(route('jobs.apply', $job), [
                'cv' => $file,
                'cover_letter' => 'I am a great fit.'
            ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('job_applications', [
            'job_id' => $job->id,
            'applicant_id' => $candidate->id,
            'status' => 'pending'
        ]);

        $application = JobApplication::first();
        $this->assertNotNull($application->resume_path);
        Storage::disk('public')->assertExists($application->resume_path);
    }

    public function test_candidate_cannot_apply_without_cv()
    {
        $candidate = User::factory()->create(['role' => 'candidate']);
        $employer = User::factory()->create(['role' => 'employer']);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Laravel Developer needed',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        $response = $this->actingAs($candidate)
            ->post(route('jobs.apply', $job), [
                'cover_letter' => 'I am a great fit.'
            ]);

        $response->assertSessionHasErrors(['cv']);
        $this->assertDatabaseCount('job_applications', 0);
    }

    public function test_candidate_cannot_apply_twice()
    {
        Storage::fake('public');
        $candidate = User::factory()->create(['role' => 'candidate']);
        $employer = User::factory()->create(['role' => 'employer']);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Laravel Developer needed',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        $file = UploadedFile::fake()->create('cv.pdf', 100);

        // First application
        $this->actingAs($candidate)
            ->post(route('jobs.apply', $job), [
                'cv' => $file,
                'cover_letter' => 'First application.'
            ]);

        // Second application attempt
        $response = $this->actingAs($candidate)
            ->post(route('jobs.apply', $job), [
                'cv' => $file,
                'cover_letter' => 'Another application.'
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('job_applications', 1);
    }

    public function test_employer_can_update_application_status_and_notify_candidate()
    {
        Notification::fake();

        $employer = User::factory()->create(['role' => 'employer']);
        $candidate = User::factory()->create(['role' => 'candidate']);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Laravel Developer needed',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        $application = JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $candidate->id,
            'status' => 'pending',
            'applied_at' => now()
        ]);

        $response = $this->actingAs($employer)
            ->put(route('employer.jobs.update-application-status', $application), [
                'status' => 'shortlisted'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'status' => 'shortlisted'
        ]);

        Notification::assertSentTo(
            [$candidate],
            ApplicationStatusChanged::class
        );
    }
}
