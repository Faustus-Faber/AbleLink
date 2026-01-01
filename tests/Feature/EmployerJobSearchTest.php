<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// F9 - Evan Yuvraj Munshi
class EmployerJobSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_can_search_jobs()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        
        Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Develop software',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        Job::create([
            'employer_id' => $employer->id,
            'title' => 'Product Manager',
            'description' => 'Manage product',
            'job_type' => 'part-time',
            'status' => 'draft'
        ]);

        $response = $this->actingAs($employer)
            ->get(route('employer.jobs.index', ['search' => 'Software']));

        $response->assertStatus(200);
        $response->assertSee('Software Engineer');
        $response->assertDontSee('Product Manager');
    }

    public function test_employer_can_filter_jobs_by_status()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        
        Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Develop software',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        Job::create([
            'employer_id' => $employer->id,
            'title' => 'Product Manager',
            'description' => 'Manage product',
            'job_type' => 'part-time',
            'status' => 'draft'
        ]);

        $response = $this->actingAs($employer)
            ->get(route('employer.jobs.index', ['status' => 'draft']));

        $response->assertStatus(200);
        $response->assertDontSee('Software Engineer');
        $response->assertSee('Product Manager');
    }

    public function test_employer_can_search_applications()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $applicant1 = User::factory()->create(['name' => 'John Doe']);
        UserProfile::create(['user_id' => $applicant1->id]);
        $applicant2 = User::factory()->create(['name' => 'Jane Smith']);
        UserProfile::create(['user_id' => $applicant2->id]);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Develop software',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $applicant1->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $applicant2->id,
            'status' => 'reviewing',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($employer)
            ->get(route('employer.applications', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    public function test_employer_can_filter_applications_by_status()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        $applicant1 = User::factory()->create(['name' => 'John Doe']);
        UserProfile::create(['user_id' => $applicant1->id]);
        $applicant2 = User::factory()->create(['name' => 'Jane Smith']);
        UserProfile::create(['user_id' => $applicant2->id]);
        
        $job = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Develop software',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $applicant1->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $applicant2->id,
            'status' => 'reviewing',
            'applied_at' => now(),
        ]);

        $response = $this->actingAs($employer)
            ->get(route('employer.applications', ['status' => 'reviewing']));

        $response->assertStatus(200);
        $response->assertDontSee('John Doe');
        $response->assertSee('Jane Smith');
    }
}
