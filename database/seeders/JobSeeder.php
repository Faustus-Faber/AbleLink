<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employment\Job;
use App\Models\Auth\User;
use App\Models\Employment\EmployerProfile;
use Carbon\Carbon;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employer = User::where('email', 'employer@test3.com')->first();

        if (!$employer) {
            $employer = User::create([
                'name' => 'Employer Test 3',
                'email' => 'employer@test3.com',
                'password' => bcrypt('password'),
                'role' => 'employer',
            ]);
            
            EmployerProfile::create([
                'user_id' => $employer->id,
                'company_name' => 'Inclusive Corp',
                'company_description' => 'A company dedicated to inclusive hiring.',
                'industry' => 'Technology',
                'company_size' => '51-200',
                'website' => 'https://inclusive-corp.test',
                'address' => '123 Accessibility Lane',
            ]);
        }

        // Check if profile exists, if not create it
        if (!$employer->employerProfile) {
             EmployerProfile::create([
                'user_id' => $employer->id,
                'company_name' => 'Inclusive Corp',
                'company_description' => 'A company dedicated to inclusive hiring.',
                'industry' => 'Technology',
                'company_size' => '51-200',
                'website' => 'https://inclusive-corp.test',
                'address' => '123 Accessibility Lane',
            ]);
        }

        $jobs = [
            ['title' => 'Software Engineer (Accessibility Focus)', 'type' => 'full-time', 'location' => 'Remote'],
            ['title' => 'Customer Support Specialist', 'type' => 'part-time', 'location' => 'New York, NY'],
            ['title' => 'Data Analyst', 'type' => 'full-time', 'location' => 'San Francisco, CA'],
            ['title' => 'Content Writer', 'type' => 'contract', 'location' => 'Remote'],
            ['title' => 'Graphic Designer', 'type' => 'freelance', 'location' => 'Austin, TX'],
            ['title' => 'HR Manager', 'type' => 'full-time', 'location' => 'Chicago, IL'],
            ['title' => 'Digital Marketing Specialist', 'type' => 'full-time', 'location' => 'Remote'],
            ['title' => 'UX/UI Designer', 'type' => 'contract', 'location' => 'Seattle, WA'],
            ['title' => 'Project Manager', 'type' => 'full-time', 'location' => 'Boston, MA'],
            ['title' => 'Sales Representative', 'type' => 'full-time', 'location' => 'Miami, FL'],
            ['title' => 'QA Tester', 'type' => 'part-time', 'location' => 'Remote'],
            ['title' => 'DevOps Engineer', 'type' => 'full-time', 'location' => 'Denver, CO'],
            ['title' => 'System Administrator', 'type' => 'full-time', 'location' => 'Atlanta, GA'],
            ['title' => 'Business Analyst', 'type' => 'full-time', 'location' => 'Remote'],
            ['title' => 'Accountant', 'type' => 'part-time', 'location' => 'Los Angeles, CA'],
            ['title' => 'Legal Consultant', 'type' => 'contract', 'location' => 'Washington, DC'],
            ['title' => 'Medical Transcriptionist', 'type' => 'part-time', 'location' => 'Remote'],
            ['title' => 'Virtual Assistant', 'type' => 'part-time', 'location' => 'Remote'],
            ['title' => 'Community Manager', 'type' => 'full-time', 'location' => 'Portland, OR'],
            ['title' => 'Technical Writer', 'type' => 'contract', 'location' => 'Remote'],
            ['title' => 'Product Owner', 'type' => 'full-time', 'location' => 'San Jose, CA'],
            ['title' => 'Cybersecurity Analyst', 'type' => 'full-time', 'location' => 'Remote'],
            ['title' => 'Event Planner', 'type' => 'contract', 'location' => 'Las Vegas, NV'],
            ['title' => 'Social Media Manager', 'type' => 'part-time', 'location' => 'Nashville, TN'],
            ['title' => 'Video Editor', 'type' => 'contract', 'location' => 'Remote'],
            ['title' => 'Translator (Spanish/English)', 'type' => 'freelance', 'location' => 'Remote'],
            ['title' => 'IT Support Specialist', 'type' => 'full-time', 'location' => 'Phoenix, AZ'],
            ['title' => 'Operations Manager', 'type' => 'full-time', 'location' => 'Detroit, MI'],
            ['title' => 'Research Scientist', 'type' => 'full-time', 'location' => 'Cambridge, MA'],
            ['title' => 'Accessibility Consultant', 'type' => 'contract', 'location' => 'Remote'],
        ];

        foreach ($jobs as $index => $jobData) {
            Job::create([
                'employer_id' => $employer->id,
                'title' => $jobData['title'],
                'description' => "We are looking for a talented {$jobData['title']} to join our team. \n\n**Responsibilities:**\n- Responsibility 1\n- Responsibility 2\n- Responsibility 3\n\n**Requirements:**\n- Requirement A\n- Requirement B\n\nThis role is committed to inclusivity and providing necessary accommodations.",
                'location' => $jobData['location'],
                'job_type' => $jobData['type'] === 'freelance' ? 'contract' : $jobData['type'], // Map freelance to contract if enum restriction
                'salary_min' => rand(40000, 60000),
                'salary_max' => rand(65000, 120000),
                'salary_currency' => 'USD',
                'application_deadline' => Carbon::now()->addDays(rand(10, 60)),
                'status' => 'active',
                'wheelchair_accessible' => (bool)rand(0, 1),
                'sign_language_support' => (bool)rand(0, 1),
                'screen_reader_compatible' => (bool)rand(0, 1),
                'flexible_hours' => (bool)rand(0, 1),
                'remote_work_available' => str_contains($jobData['location'], 'Remote'),
                'accessibility_accommodations' => 'We offer screen reader software, adjustable desks, and flexible scheduling.',
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
