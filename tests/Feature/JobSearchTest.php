<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// F9 - Evan Yuvraj Munshi
class JobSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_job_search_page()
    {
        $response = $this->get(route('jobs.index'));
        $response->assertStatus(200);
        $response->assertViewIs('jobs.index');
    }

    public function test_can_search_jobs_by_keyword()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        
        $job1 = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Software Engineer',
            'description' => 'Laravel Developer needed',
            'job_type' => 'full-time',
            'status' => 'active'
        ]);

        $job2 = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Designer',
            'description' => 'UI/UX Designer',
            'job_type' => 'part-time',
            'status' => 'active'
        ]);

        $response = $this->get(route('jobs.index', ['search' => 'Laravel']));
        
        $response->assertStatus(200);
        $response->assertSee('Software Engineer');
        $response->assertDontSee('Designer');
    }

    public function test_can_filter_jobs_by_accessibility()
    {
        $employer = User::factory()->create(['role' => 'employer']);
        
        $job1 = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Accessible Job',
            'description' => 'Test',
            'job_type' => 'full-time',
            'status' => 'active',
            'wheelchair_accessible' => true
        ]);

        $job2 = Job::create([
            'employer_id' => $employer->id,
            'title' => 'Non-Accessible Job',
            'description' => 'Test',
            'job_type' => 'full-time',
            'status' => 'active',
            'wheelchair_accessible' => false
        ]);

        $response = $this->get(route('jobs.index', ['wheelchair_accessible' => '1']));
        
        $response->assertStatus(200);
        $response->assertSee('Accessible Job');
        $response->assertDontSee('Non-Accessible Job');
    }
}
