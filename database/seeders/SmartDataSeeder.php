<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\User;
use App\Models\Employment\Job;
use App\Models\Education\Course;
use App\Models\Employment\EmployerProfile;
use App\Models\Auth\UserProfile;
use Carbon\Carbon;

class SmartDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean up existing data to avoid conflicts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Job::truncate();
        Course::truncate();
        DB::table('course_media')->truncate(); 
        // Also truncate profiles to ensure clean slate for company names
        EmployerProfile::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Existing jobs, courses, and profiles cleared.');

        // 2. Ensure Users Exist for all roles
        try {
             $users = $this->ensureUsersExist();
        } catch (\Exception $e) {
            $this->command->error('Error creating users: ' . $e->getMessage());
            return;
        }

        // 3. Seed High-Quality Jobs (LinkedIn Style)
        try {
             $this->seedJobs($users['employers']);
        } catch (\Exception $e) {
             $this->command->error('Error seeding jobs: ' . $e->getMessage());
        }

        // 4. Seed High-Quality Courses
        try {
            $this->seedCourses();
        } catch (\Exception $e) {
             $this->command->error('Error seeding courses: ' . $e->getMessage());
        }
    }

    private function ensureUsersExist()
    {
        $createdUsers = [];

        // Admin
        $admin = $this->getOrCreateUser('admin@ablelink.com', 'System Admin', 'admin');

        // Employers
        $employers = [];
        for ($i = 1; $i <= 3; $i++) {
            $e = $this->getOrCreateUser("employer{$i}@test.com", "Employer {$i}", 'employer');
            
            if ($e) {
                // Try to create profile, ignore if fails
                try {
                     EmployerProfile::firstOrCreate(
                        ['user_id' => $e->id],
                        [
                            'company_name' => "Tech Inclusive {$i}",
                            'company_description' => "Leading the way in accessible technology.",
                            'industry' => 'Technology',
                            'company_size' => '100-500',
                            'address' => '123 Tech Park',
                            'website' => 'https://example.com'
                        ]
                    );
                } catch (\Exception $ex) {}
                $employers[] = $e;
            }
        }

        // Candidates (Disabled) - Add Skills & Interests
        $skillsList = ['PHP', 'Laravel', 'React', 'Vue', 'Python', 'Data Analysis', 'Design', 'Writing', 'Accessibility testing'];
        $interestsList = ['Programming', 'Design', 'Marketing', 'Business', 'Wellness', 'Communication'];

        for ($i = 1; $i <= 3; $i++) {
            $u = $this->getOrCreateUser("disabled{$i}@test.com", "Candidate {$i}", 'disabled');
            if ($u && $u->profile) {
                $u->profile->update([
                    'skills' => collect($skillsList)->random(rand(2, 4))->values()->toArray(),
                    'interests' => collect($interestsList)->random(rand(2, 3))->values()->toArray(),
                    'learning_style' => ['visual', 'auditory', 'text'][rand(0, 2)],
                ]);
            }
        }

        // Caregivers/Volunteers - Add soft skills/interests
        for ($i = 1; $i <= 2; $i++) {
            $c = $this->getOrCreateUser("caregiver{$i}@test.com", "Caregiver {$i}", 'caregiver');
            if ($c && $c->profile) {
                $c->profile->update(['interests' => ['Health', 'Wellness', 'Communication', 'First Aid']]);
            }
        }

        return ['employers' => $employers, 'admin' => $admin];
    }

    private function getOrCreateUser($email, $name, $role)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            try {
                $user = User::create([
                    'email' => $email,
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'role' => $role
                ]);
                // Create profile stub
                UserProfile::create(['user_id' => $user->id]);
            } catch (\Exception $e) { 
                 $user = User::where('email', $email)->first();
            }
        } else {
             if (!$user->profile) UserProfile::create(['user_id' => $user->id]);
        }
        return $user;
    }

    private function seedJobs($employers)
    {
        $jobTitles = [
            'Frontend Developer (React/Vue)', 'Backend Engineer (Laravel/Node)', 'Full Stack Developer',
            'UI/UX Designer', 'Product Manager', 'Data Scientist', 'Customer Support Specialist',
            'Digital Marketing Manager', 'Content Writer', 'HR Specialist', 'Accessibility Consultant',
            'QA Engineer', 'DevOps Specialist', 'Business Analyst', 'Social Media Coordinator'
        ];

        $locations = ['Remote', 'New York, NY', 'San Francisco, CA', 'London, UK', 'Toronto, ON', 'Austin, TX'];
        
        // Define skills map for better matching relevance
        $skillsMap = [
            'Developer' => ['React', 'Vue', 'PHP', 'Laravel', 'Python'],
            'Engineer' => ['Node', 'DevOps', 'Python', 'Java'],
            'Designer' => ['Design', 'UI/UX', 'Figma', 'Adobe'],
            'Manager' => ['Management', 'Communication', 'Agile'],
            'Writer' => ['Writing', 'Content', 'SEO'],
            'Analyst' => ['Data Analysis', 'Excel', 'Python'],
        ];

        foreach ($jobTitles as $index => $title) {
            $employer = $employers[$index % count($employers)];
            $isRemote = str_contains($locations[$index % count($locations)], 'Remote');
            
            // Derive skills from title
            $requiredSkills = [];
            foreach ($skillsMap as $key => $skills) {
                if (str_contains($title, $key)) {
                    $requiredSkills = array_merge($requiredSkills, $skills);
                }
            }
            if (empty($requiredSkills)) $requiredSkills = ['Communication', 'Office 365'];

            Job::create([
                'employer_id' => $employer->id,
                'title' => $title,
                'location' => $locations[$index % count($locations)],
                'job_type' => $index % 2 == 0 ? 'full-time' : 'contract',
                'salary_min' => rand(50000, 80000),
                'salary_max' => rand(85000, 150000),
                'salary_currency' => 'USD',
                'application_deadline' => Carbon::now()->addDays(rand(14, 60)),
                'status' => 'active',
                'wheelchair_accessible' => true,
                'sign_language_support' => (bool)rand(0, 1),
                'screen_reader_compatible' => true,
                'flexible_hours' => true,
                'remote_work_available' => $isRemote,
                'accessibility_accommodations' => 'We provide specialized hardware, screen reader licenses, and flexible scheduling for therapy appointments.',
                'description' => $this->generateLinkedInDescription($title),
                // F12 New Fields
                'skills_required' => $requiredSkills,
                'embedding_vector' => array_map(fn() => rand(0, 100) / 100, range(1, 5)), // Simulating 5-dim vector
            ]);
        }
    }

    private function generateLinkedInDescription($title)
    {
        return "
**About the Role**
We are looking for a passionate **{$title}** to join our diverse team. In this role, you will have the opportunity to work on cutting-edge projects that impact millions of users. We value innovation, collaboration, and inclusivity.

**Key Responsibilities**
*   Collaborate with cross-functional teams to define, design, and ship new features.
*   Write clean, maintainable, and efficient code.
*   Participate in code reviews and advocate for best practices.
*   Troubleshoot and resolve complex technical issues.
*   Ensure the performance, quality, and responsiveness of applications.
*   Mentor junior team members and contribute to internal documentation.

**Qualifications**
*   Proven work experience as a {$title} or similar role.
*   Strong understanding of accessibility standards (WCAG 2.1).
*   Experience with Agile methodologies.
*   Excellent problem-solving skills and attention to detail.
*   Strong verbal and written communication skills.
*   Ability to work effectively in a remote or hybrid environment.

**What We Offer**
*   Competitive salary and equity package.
*   Comprehensive health, dental, and vision insurance.
*   Generous paid time off and parental leave.
*   Professional development budget.
*   A supportive and inclusive work culture.

**About Us**
We are a forward-thinking company dedicated to creating accessible digital experiences for everyone. We believe that diversity drives innovation and are committed to building a team that reflects the world we live in.
        ";
    }

    private function seedCourses()
    {
        $courses = [
            ['title' => 'Web Accessibility Fundamentals', 'category' => 'Development'],
            ['title' => 'Python for Data Science', 'category' => 'Data Science'],
            ['title' => 'Digital Marketing 101', 'category' => 'Marketing'],
            ['title' => 'Graphic Design Masterclass', 'category' => 'Design'],
            ['title' => 'Leadership & Management Skills', 'category' => 'Business'],
            ['title' => 'Introduction to Sign Language', 'category' => 'Communication'],
            ['title' => 'Financial Literacy for Beginners', 'category' => 'Finance'],
            ['title' => 'Mindfulness and Stress Management', 'category' => 'Wellness'],
            ['title' => 'Creative Writing Workshop', 'category' => 'Arts'],
            ['title' => 'Cybersecurity Basics', 'category' => 'IT'],
            ['title' => 'Project Management Professional (PMP) Prep', 'category' => 'Business'],
            ['title' => 'Public Speaking with Confidence', 'category' => 'Communication'],
        ];

        foreach ($courses as $course) {
            $tags = [$course['category']];
            if (str_contains($course['title'], 'Design')) $tags[] = 'Creative';
            if (str_contains($course['title'], 'Python') || str_contains($course['title'], 'Web')) $tags[] = 'Programming';
            if (str_contains($course['title'], 'Management')) $tags[] = 'Leadership';

            Course::create([
                'title' => $course['title'],
                'slug' => \Illuminate\Support\Str::slug($course['title']),
                'category' => $course['category'], // Populating new field
                'summary' => "A comprehensive course on {$course['title']} in the {$course['category']} field.",
                'description' => $this->generateCourseDescription($course['title']),
                'level' => ['beginner', 'intermediate', 'advanced'][rand(0, 2)],
                'estimated_minutes' => rand(60, 600),
                'published_at' => Carbon::now(),
                // F12 New Fields
                'tags' => $tags,
            ]);
        }
    }

    private function generateCourseDescription($title)
    {
        return "
**Course Overview**
Master the skills needed for **{$title}** in this comprehensive course. Designed for learners of all levels, this curriculum covers everything from the basics to advanced concepts.

**What You Will Learn**
*   Understand the core principles of {$title}.
*   Apply practical techniques to real-world scenarios.
*   Gain hands-on experience through interactive projects.
*   Develop a portfolio to showcase your new skills.
*   Learn from industry experts with years of experience.

**Who This Course Is For**
*   Beginners looking to start a career in this field.
*   Professionals wanting to upskill or pivot careers.
*   Students seeking practical knowledge to supplement their studies.
*   Anyone interested in personal growth and development.

**Requirements**
*   No prior experience required.
*   A computer with internet access.
*   A passion for learning!
        ";
    }
}
