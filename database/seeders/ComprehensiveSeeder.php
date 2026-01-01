<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Auth\User;
use App\Models\Auth\UserProfile;
use App\Models\Employment\Job;
use App\Models\Employment\EmployerProfile;
use App\Models\Education\Course;
use App\Models\Community\MatrimonyProfile;
use App\Models\Community\CommunityEvent;
use App\Models\Community\AssistanceRequest;
use App\Models\Community\ForumThread;
use Carbon\Carbon;

class ComprehensiveSeeder extends Seeder
{
    private $employers = [];
    private $allUsers = [];
    private $admin = null;

    public function run(): void
    {
        $this->command->info('🚀 Starting Comprehensive Data Seeder...');

        // Truncate existing data
        $this->cleanDatabase();

        // Seed in order
        $this->seedUsers();
        $this->seedJobs();
        $this->seedCourses();
        $this->seedMatrimonyProfiles();
        $this->seedEvents();
        $this->seedAssistanceRequests();
        $this->seedAidPrograms();
        $this->seedForumPosts();

        $this->command->info('✅ Comprehensive seeding completed!');
    }

    private function cleanDatabase(): void
    {
        $this->command->info('🧹 Cleaning existing data...');
        
        // Disable foreign key checks - cross-platform approach
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } catch (\Exception $e) {
            // SQLite or other DB might not support this
        }
        
        try {
            DB::table('aid_programs')->truncate();
        } catch (\Exception $e) {}
        
        try {
            DB::table('forum_replies')->truncate();
            ForumThread::truncate();
        } catch (\Exception $e) {}
        
        try {
            AssistanceRequest::truncate();
        } catch (\Exception $e) {}
        
        try {
            CommunityEvent::truncate();
        } catch (\Exception $e) {}
        
        try {
            MatrimonyProfile::truncate();
        } catch (\Exception $e) {}
        
        try {
            Course::truncate();
            DB::table('course_media')->truncate();
        } catch (\Exception $e) {}
        
        try {
            Job::truncate();
        } catch (\Exception $e) {}
        
        try {
            EmployerProfile::truncate();
        } catch (\Exception $e) {}
        
        try {
            UserProfile::truncate();
        } catch (\Exception $e) {}
        
        try {
            DB::table('caregiver_user')->truncate();
        } catch (\Exception $e) {}
        
        try {
            User::truncate();
        } catch (\Exception $e) {}
        
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } catch (\Exception $e) {}
    }


    private function seedUsers(): void
    {
        $this->command->info('👥 Creating 20 users...');

        // 1 Admin
        $this->admin = $this->createUser('Admin User', 'admin@ablelink.com', 'admin');

        // 8 Disabled users
        for ($i = 1; $i <= 8; $i++) {
            $this->allUsers[] = $this->createUser("Disabled User $i", "disabled$i@ablelink.com", 'disabled');
        }

        // 4 Caregivers
        for ($i = 1; $i <= 4; $i++) {
            $this->allUsers[] = $this->createUser("Caregiver $i", "caregiver$i@ablelink.com", 'caregiver');
        }

        // 4 Employers
        for ($i = 1; $i <= 4; $i++) {
            $employer = $this->createUser("Employer $i", "employer$i@ablelink.com", 'employer');
            $this->employers[] = $employer;
            $this->allUsers[] = $employer;
            
            EmployerProfile::create([
                'user_id' => $employer->id,
                'company_name' => $this->getCompanyNames()[$i - 1],
                'company_description' => 'A company committed to inclusive hiring and accessible workplaces.',
                'industry' => ['Technology', 'Healthcare', 'Finance', 'Retail'][$i - 1],
                'company_size' => [100, 350, 750, 1500][$i - 1],
                'website' => "https://company$i.example.com",
                'address' => "123 Business St, Suite $i",
            ]);

        }

        // 3 Volunteers
        for ($i = 1; $i <= 3; $i++) {
            $this->allUsers[] = $this->createUser("Volunteer $i", "volunteer$i@ablelink.com", 'volunteer');
        }

        $this->allUsers[] = $this->admin;
        $this->command->info('   Created 20 users (1 admin, 8 disabled, 4 caregivers, 4 employers, 3 volunteers)');
    }

    private function createUser($name, $email, $role): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
            'email_verified_at' => now(),
            'otp_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'skills' => $this->getRandomSkills(),
            'interests' => $this->getRandomInterests(),
            'learning_style' => ['visual', 'auditory', 'kinesthetic', 'reading'][rand(0, 3)],
        ]);

        return $user;
    }

    private function getCompanyNames(): array
    {
        return ['TechCorp Inclusive', 'HealthCare Plus', 'FinanceForAll', 'RetailConnect'];
    }

    private function getRandomSkills(): array
    {
        $skills = ['PHP', 'Laravel', 'JavaScript', 'React', 'Python', 'Data Analysis', 'Writing', 'Design', 'Communication', 'Project Management', 'Customer Service', 'Marketing'];
        return array_values(array_slice(collect($skills)->shuffle()->toArray(), 0, rand(2, 4)));
    }

    private function getRandomInterests(): array
    {
        $interests = ['Technology', 'Health', 'Education', 'Arts', 'Sports', 'Music', 'Reading', 'Travel', 'Cooking', 'Volunteering'];
        return array_values(array_slice(collect($interests)->shuffle()->toArray(), 0, rand(2, 4)));
    }

    private function seedJobs(): void
    {
        $this->command->info('💼 Creating 100 jobs...');
        
        $jobData = $this->getJobData();
        
        foreach ($jobData as $index => $job) {
            $employer = $this->employers[$index % count($this->employers)];
            
            Job::create([
                'employer_id' => $employer->id,
                'title' => $job['title'],
                'description' => $job['description'],
                'location' => $job['location'],
                'job_type' => $job['type'],
                'salary_min' => $job['salary_min'],
                'salary_max' => $job['salary_max'],
                'salary_currency' => 'USD',
                'application_deadline' => Carbon::now()->addDays(rand(14, 90)),
                'status' => 'active',
                'wheelchair_accessible' => (bool)rand(0, 1),
                'sign_language_support' => (bool)rand(0, 1),
                'screen_reader_compatible' => true,
                'flexible_hours' => (bool)rand(0, 1),
                'remote_work_available' => str_contains($job['location'], 'Remote'),
                'accessibility_accommodations' => 'We provide assistive technology, flexible scheduling, and accessibility accommodations as needed.',
                'skills_required' => $job['skills'],
            ]);
        }
        
        $this->command->info('   Created 100 jobs across 4 employers');
    }

    private function getJobData(): array
    {
        $jobs = [];
        $categories = [
            'Technology' => [
                ['title' => 'Senior Software Engineer', 'skills' => ['PHP', 'Laravel', 'MySQL']],
                ['title' => 'Frontend Developer', 'skills' => ['JavaScript', 'React', 'CSS']],
                ['title' => 'Full Stack Developer', 'skills' => ['Node.js', 'React', 'PostgreSQL']],
                ['title' => 'DevOps Engineer', 'skills' => ['AWS', 'Docker', 'Kubernetes']],
                ['title' => 'Data Scientist', 'skills' => ['Python', 'Machine Learning', 'SQL']],
                ['title' => 'Mobile App Developer', 'skills' => ['React Native', 'iOS', 'Android']],
                ['title' => 'QA Engineer', 'skills' => ['Selenium', 'Testing', 'Automation']],
                ['title' => 'Cloud Architect', 'skills' => ['AWS', 'Azure', 'GCP']],
                ['title' => 'Cybersecurity Analyst', 'skills' => ['Security', 'Networking', 'Risk Assessment']],
                ['title' => 'AI/ML Engineer', 'skills' => ['TensorFlow', 'PyTorch', 'NLP']],
            ],
            'Healthcare' => [
                ['title' => 'Healthcare Administrator', 'skills' => ['Administration', 'Compliance', 'Management']],
                ['title' => 'Medical Transcriptionist', 'skills' => ['Medical Terminology', 'Typing', 'Attention to Detail']],
                ['title' => 'Telehealth Coordinator', 'skills' => ['Communication', 'Healthcare Tech', 'Patient Care']],
                ['title' => 'Health Data Analyst', 'skills' => ['Data Analysis', 'Healthcare', 'Excel']],
                ['title' => 'Patient Advocate', 'skills' => ['Communication', 'Empathy', 'Problem Solving']],
                ['title' => 'Medical Coder', 'skills' => ['ICD-10', 'CPT', 'Medical Records']],
                ['title' => 'Clinical Research Coordinator', 'skills' => ['Research', 'Compliance', 'Data Management']],
                ['title' => 'Health Information Technician', 'skills' => ['EHR Systems', 'Data Entry', 'HIPAA']],
                ['title' => 'Mental Health Counselor', 'skills' => ['Counseling', 'Psychology', 'Empathy']],
                ['title' => 'Pharmacy Technician', 'skills' => ['Pharmacy', 'Customer Service', 'Attention to Detail']],
            ],
            'Finance' => [
                ['title' => 'Financial Analyst', 'skills' => ['Excel', 'Financial Modeling', 'Analysis']],
                ['title' => 'Accountant', 'skills' => ['Accounting', 'QuickBooks', 'Compliance']],
                ['title' => 'Tax Specialist', 'skills' => ['Tax Law', 'Accounting', 'Attention to Detail']],
                ['title' => 'Investment Advisor', 'skills' => ['Investment', 'Client Relations', 'Analysis']],
                ['title' => 'Compliance Officer', 'skills' => ['Regulations', 'Risk Management', 'Auditing']],
                ['title' => 'Budget Analyst', 'skills' => ['Budgeting', 'Financial Planning', 'Excel']],
                ['title' => 'Payroll Specialist', 'skills' => ['Payroll', 'HR', 'Software']],
                ['title' => 'Credit Analyst', 'skills' => ['Credit Analysis', 'Financial Reports', 'Risk']],
                ['title' => 'Insurance Claims Processor', 'skills' => ['Claims Processing', 'Insurance', 'Detail']],
                ['title' => 'Financial Planner', 'skills' => ['Financial Planning', 'Wealth Management', 'Client Service']],
            ],
            'Marketing' => [
                ['title' => 'Digital Marketing Manager', 'skills' => ['SEO', 'SEM', 'Analytics']],
                ['title' => 'Content Writer', 'skills' => ['Writing', 'SEO', 'Research']],
                ['title' => 'Social Media Manager', 'skills' => ['Social Media', 'Content Creation', 'Analytics']],
                ['title' => 'Email Marketing Specialist', 'skills' => ['Email Marketing', 'Copywriting', 'A/B Testing']],
                ['title' => 'Brand Strategist', 'skills' => ['Branding', 'Strategy', 'Market Research']],
                ['title' => 'Marketing Coordinator', 'skills' => ['Marketing', 'Coordination', 'Communication']],
                ['title' => 'SEO Specialist', 'skills' => ['SEO', 'Analytics', 'Content Strategy']],
                ['title' => 'PPC Specialist', 'skills' => ['Google Ads', 'PPC', 'Analytics']],
                ['title' => 'Market Research Analyst', 'skills' => ['Research', 'Data Analysis', 'Reports']],
                ['title' => 'Public Relations Specialist', 'skills' => ['PR', 'Communications', 'Media Relations']],
            ],
            'Creative' => [
                ['title' => 'UI/UX Designer', 'skills' => ['Figma', 'User Research', 'Prototyping']],
                ['title' => 'Graphic Designer', 'skills' => ['Adobe Creative Suite', 'Design', 'Typography']],
                ['title' => 'Video Editor', 'skills' => ['Premiere Pro', 'After Effects', 'Storytelling']],
                ['title' => 'Technical Writer', 'skills' => ['Writing', 'Documentation', 'Technical Knowledge']],
                ['title' => 'Instructional Designer', 'skills' => ['E-Learning', 'Curriculum Design', 'LMS']],
                ['title' => 'Motion Graphics Designer', 'skills' => ['After Effects', 'Animation', 'Design']],
                ['title' => 'Copywriter', 'skills' => ['Writing', 'Advertising', 'Creativity']],
                ['title' => 'Art Director', 'skills' => ['Art Direction', 'Creative Leadership', 'Design']],
                ['title' => 'Photographer', 'skills' => ['Photography', 'Editing', 'Composition']],
                ['title' => 'Web Designer', 'skills' => ['Web Design', 'HTML', 'CSS']],
            ],
            'Customer Service' => [
                ['title' => 'Customer Support Specialist', 'skills' => ['Communication', 'Problem Solving', 'CRM']],
                ['title' => 'Help Desk Technician', 'skills' => ['Technical Support', 'Troubleshooting', 'Patience']],
                ['title' => 'Community Manager', 'skills' => ['Community Building', 'Social Media', 'Engagement']],
                ['title' => 'Client Success Manager', 'skills' => ['Client Relations', 'Account Management', 'Communication']],
                ['title' => 'Virtual Assistant', 'skills' => ['Organization', 'Communication', 'Scheduling']],
                ['title' => 'Call Center Representative', 'skills' => ['Phone Skills', 'CRM', 'Problem Solving']],
                ['title' => 'Customer Experience Manager', 'skills' => ['CX Strategy', 'Analytics', 'Leadership']],
                ['title' => 'Technical Support Engineer', 'skills' => ['Technical Skills', 'Support', 'Documentation']],
                ['title' => 'Chat Support Agent', 'skills' => ['Typing', 'Communication', 'Multitasking']],
                ['title' => 'Support Operations Analyst', 'skills' => ['Analytics', 'Operations', 'Reporting']],
            ],
            'Human Resources' => [
                ['title' => 'HR Coordinator', 'skills' => ['HR', 'Recruitment', 'Onboarding']],
                ['title' => 'Recruiter', 'skills' => ['Sourcing', 'Interviewing', 'ATS']],
                ['title' => 'Training Specialist', 'skills' => ['Training', 'Curriculum Development', 'Facilitation']],
                ['title' => 'Compensation Analyst', 'skills' => ['Compensation', 'Benefits', 'Analysis']],
                ['title' => 'Diversity & Inclusion Specialist', 'skills' => ['DEI', 'Strategy', 'Program Management']],
                ['title' => 'HR Business Partner', 'skills' => ['HR Strategy', 'Employee Relations', 'Change Management']],
                ['title' => 'Talent Acquisition Manager', 'skills' => ['Recruiting', 'Leadership', 'Employer Branding']],
                ['title' => 'Employee Relations Specialist', 'skills' => ['Conflict Resolution', 'HR Policies', 'Communication']],
                ['title' => 'Benefits Administrator', 'skills' => ['Benefits', 'HRIS', 'Compliance']],
                ['title' => 'HR Data Analyst', 'skills' => ['HR Analytics', 'Excel', 'Reporting']],
            ],
            'Operations' => [
                ['title' => 'Project Manager', 'skills' => ['Project Management', 'Agile', 'Leadership']],
                ['title' => 'Operations Manager', 'skills' => ['Operations', 'Process Improvement', 'Management']],
                ['title' => 'Supply Chain Analyst', 'skills' => ['Supply Chain', 'Logistics', 'Analysis']],
                ['title' => 'Business Analyst', 'skills' => ['Requirements Gathering', 'Analysis', 'Documentation']],
                ['title' => 'Scrum Master', 'skills' => ['Scrum', 'Agile', 'Facilitation']],
                ['title' => 'Product Owner', 'skills' => ['Product Management', 'Agile', 'Stakeholder Management']],
                ['title' => 'Process Improvement Specialist', 'skills' => ['Lean', 'Six Sigma', 'Process Design']],
                ['title' => 'Logistics Coordinator', 'skills' => ['Logistics', 'Shipping', 'Coordination']],
                ['title' => 'Quality Assurance Manager', 'skills' => ['QA', 'Standards', 'Leadership']],
                ['title' => 'Inventory Manager', 'skills' => ['Inventory', 'ERP', 'Supply Chain']],
            ],
            'Accessibility' => [
                ['title' => 'Accessibility Consultant', 'skills' => ['WCAG', 'Accessibility Testing', 'Compliance']],
                ['title' => 'Assistive Technology Specialist', 'skills' => ['Assistive Tech', 'Training', 'Support']],
                ['title' => 'Accessibility Tester', 'skills' => ['Screen Readers', 'Testing', 'WCAG']],
                ['title' => 'Inclusive Design Specialist', 'skills' => ['UX Design', 'Inclusive Design', 'Research']],
                ['title' => 'Captioning Specialist', 'skills' => ['Captioning', 'Transcription', 'Attention to Detail']],
                ['title' => 'Accessibility Engineer', 'skills' => ['Web Development', 'ARIA', 'Accessibility']],
                ['title' => 'Digital Accessibility Analyst', 'skills' => ['WCAG', 'Audit', 'Reporting']],
                ['title' => 'Braille Transcriber', 'skills' => ['Braille', 'Transcription', 'Proofreading']],
                ['title' => 'Sign Language Interpreter', 'skills' => ['ASL', 'Interpretation', 'Communication']],
                ['title' => 'Accessibility Program Manager', 'skills' => ['Program Management', 'Accessibility', 'Leadership']],
            ],
            'Education' => [
                ['title' => 'Online Tutor', 'skills' => ['Teaching', 'Subject Expertise', 'Patience']],
                ['title' => 'Course Developer', 'skills' => ['Curriculum Design', 'E-Learning', 'Content Creation']],
                ['title' => 'Education Coordinator', 'skills' => ['Coordination', 'Administration', 'Communication']],
                ['title' => 'Academic Advisor', 'skills' => ['Advising', 'Student Support', 'Planning']],
                ['title' => 'Research Assistant', 'skills' => ['Research', 'Data Collection', 'Analysis']],
                ['title' => 'Learning Experience Designer', 'skills' => ['LXD', 'E-Learning', 'User Experience']],
                ['title' => 'Educational Content Writer', 'skills' => ['Writing', 'Education', 'Research']],
                ['title' => 'Student Success Coach', 'skills' => ['Coaching', 'Mentoring', 'Student Development']],
                ['title' => 'ESL Teacher', 'skills' => ['English', 'Teaching', 'Cross-Cultural Communication']],
                ['title' => 'Special Education Coordinator', 'skills' => ['Special Education', 'IEP', 'Advocacy']],
            ],
        ];


        $locations = ['Remote', 'New York, NY', 'San Francisco, CA', 'Austin, TX', 'Chicago, IL', 'Boston, MA', 'Seattle, WA', 'Denver, CO', 'Remote - Hybrid', 'Los Angeles, CA'];
        $types = ['full-time', 'part-time', 'contract', 'internship'];

        $index = 0;
        foreach ($categories as $category => $categoryJobs) {
            foreach ($categoryJobs as $job) {
                $jobs[] = [
                    'title' => $job['title'],
                    'skills' => $job['skills'],
                    'location' => $locations[$index % count($locations)],
                    'type' => $types[$index % count($types)],
                    'salary_min' => rand(40000, 80000),
                    'salary_max' => rand(85000, 180000),
                    'description' => $this->generateJobDescription($job['title'], $category, $job['skills']),
                ];
                $index++;
            }
        }

        return $jobs;
    }

    private function generateJobDescription($title, $category, $skills): string
    {
        $skillsList = implode(', ', $skills);
        
        return "## About This Role

We are seeking a talented **{$title}** to join our {$category} team. This role offers an excellent opportunity to make a meaningful impact while working in an inclusive, accessibility-first environment.

## Key Responsibilities

- Lead and execute projects within the {$category} domain
- Collaborate with cross-functional teams to deliver high-quality solutions
- Apply expertise in {$skillsList} to solve complex challenges
- Mentor team members and contribute to best practices
- Ensure all work meets our high standards for accessibility and inclusion

## Required Qualifications

- 3+ years of experience in a similar role
- Demonstrated expertise in: **{$skillsList}**
- Strong communication and collaboration skills
- Commitment to creating accessible and inclusive solutions
- Ability to work independently and as part of a team

## What We Offer

- **Competitive Salary**: Market-rate compensation with annual reviews
- **Flexible Work**: Remote and hybrid options available
- **Accessibility First**: Full accommodations and assistive technology support
- **Professional Development**: Learning budget and career growth opportunities
- **Inclusive Culture**: A workplace where everyone belongs

## Our Commitment to Accessibility

We are dedicated to creating a barrier-free workplace. We provide:
- Screen reader compatible tools and documentation
- Sign language interpretation for meetings upon request
- Flexible scheduling for medical appointments
- Ergonomic equipment and assistive technology
- Mental health support and wellness programs

*We encourage applications from candidates with disabilities and are committed to providing accommodations throughout the hiring process.*";
    }

    private function seedCourses(): void
    {
        $this->command->info('📚 Creating 100 courses...');
        
        $courseData = $this->getCourseData();
        
        foreach ($courseData as $course) {
            Course::create([
                'title' => $course['title'],
                'slug' => Str::slug($course['title']),
                'summary' => $course['summary'],
                'description' => $course['description'],
                'level' => $course['level'],
                'category' => $course['category'],
                'estimated_minutes' => $course['duration'],
                'tags' => $course['tags'],
                'published_at' => now(),
            ]);
        }
        
        $this->command->info('   Created 100 courses across multiple categories');
    }

    private function getCourseData(): array
    {
        $courses = [];
        
        $categories = [
            'Accessibility' => [
                ['title' => 'Introduction to Screen Readers', 'summary' => 'Master JAWS and NVDA basics for web navigation', 'level' => 'beginner', 'duration' => 45, 'tags' => ['accessibility', 'assistive-tech']],
                ['title' => 'WCAG 2.1 Compliance Guide', 'summary' => 'Understand web content accessibility guidelines', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['accessibility', 'compliance']],
                ['title' => 'Accessible Document Creation', 'summary' => 'Create accessible PDFs and Word documents', 'level' => 'beginner', 'duration' => 60, 'tags' => ['accessibility', 'documents']],
                ['title' => 'Keyboard Navigation Mastery', 'summary' => 'Navigate any application without a mouse', 'level' => 'beginner', 'duration' => 30, 'tags' => ['accessibility', 'productivity']],
                ['title' => 'Mobile Accessibility Features', 'summary' => 'Leverage iOS and Android accessibility tools', 'level' => 'beginner', 'duration' => 45, 'tags' => ['accessibility', 'mobile']],
                ['title' => 'Voice Control for Computing', 'summary' => 'Use Dragon and Voice Access for hands-free computing', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['accessibility', 'voice']],
                ['title' => 'Braille Display Fundamentals', 'summary' => 'Working with refreshable braille displays', 'level' => 'intermediate', 'duration' => 60, 'tags' => ['accessibility', 'braille']],
                ['title' => 'Captioning and Subtitles', 'summary' => 'Create and use captions effectively', 'level' => 'beginner', 'duration' => 45, 'tags' => ['accessibility', 'deaf-hoh']],
                ['title' => 'Magnification Tools Mastery', 'summary' => 'Screen magnification for low vision users', 'level' => 'beginner', 'duration' => 40, 'tags' => ['accessibility', 'low-vision']],
                ['title' => 'Accessible Gaming Setup', 'summary' => 'Configure games for accessibility', 'level' => 'intermediate', 'duration' => 55, 'tags' => ['accessibility', 'gaming']],
            ],
            'Technology' => [
                ['title' => 'Python Programming Basics', 'summary' => 'Start your coding journey with Python', 'level' => 'beginner', 'duration' => 120, 'tags' => ['programming', 'python']],
                ['title' => 'Web Development Fundamentals', 'summary' => 'HTML, CSS, and JavaScript essentials', 'level' => 'beginner', 'duration' => 180, 'tags' => ['programming', 'web']],
                ['title' => 'Data Analysis with Excel', 'summary' => 'Master spreadsheets for data work', 'level' => 'beginner', 'duration' => 90, 'tags' => ['data', 'excel']],
                ['title' => 'Introduction to AI Tools', 'summary' => 'Use ChatGPT and AI assistants effectively', 'level' => 'beginner', 'duration' => 60, 'tags' => ['ai', 'productivity']],
                ['title' => 'Cloud Computing Essentials', 'summary' => 'Understanding cloud services', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['cloud', 'technology']],
                ['title' => 'Cybersecurity Basics', 'summary' => 'Protect yourself and your data online', 'level' => 'beginner', 'duration' => 75, 'tags' => ['security', 'safety']],
                ['title' => 'Mobile App Development Intro', 'summary' => 'Build your first mobile app', 'level' => 'intermediate', 'duration' => 150, 'tags' => ['programming', 'mobile']],
                ['title' => 'Database Fundamentals', 'summary' => 'Learn SQL and data management', 'level' => 'intermediate', 'duration' => 120, 'tags' => ['data', 'sql']],
                ['title' => 'Version Control with Git', 'summary' => 'Collaborate on code projects', 'level' => 'beginner', 'duration' => 60, 'tags' => ['programming', 'git']],
                ['title' => 'API Integration Basics', 'summary' => 'Connect applications together', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['programming', 'api']],
            ],
            'Career Development' => [
                ['title' => 'Resume Writing Workshop', 'summary' => 'Create a compelling resume that stands out', 'level' => 'beginner', 'duration' => 60, 'tags' => ['career', 'resume']],
                ['title' => 'Interview Skills Mastery', 'summary' => 'Ace your job interviews with confidence', 'level' => 'beginner', 'duration' => 75, 'tags' => ['career', 'interview']],
                ['title' => 'LinkedIn Profile Optimization', 'summary' => 'Build a professional online presence', 'level' => 'beginner', 'duration' => 45, 'tags' => ['career', 'networking']],
                ['title' => 'Workplace Communication', 'summary' => 'Effective communication strategies', 'level' => 'beginner', 'duration' => 60, 'tags' => ['career', 'communication']],
                ['title' => 'Negotiation Skills', 'summary' => 'Negotiate salary and benefits effectively', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['career', 'negotiation']],
                ['title' => 'Remote Work Success', 'summary' => 'Thrive in a remote work environment', 'level' => 'beginner', 'duration' => 45, 'tags' => ['career', 'remote']],
                ['title' => 'Self-Advocacy at Work', 'summary' => 'Request accommodations with confidence', 'level' => 'intermediate', 'duration' => 60, 'tags' => ['career', 'advocacy']],
                ['title' => 'Disability Disclosure Guide', 'summary' => 'When and how to disclose disability', 'level' => 'intermediate', 'duration' => 50, 'tags' => ['career', 'disclosure']],
                ['title' => 'Professional Networking', 'summary' => 'Build meaningful professional connections', 'level' => 'beginner', 'duration' => 55, 'tags' => ['career', 'networking']],
                ['title' => 'Freelancing Fundamentals', 'summary' => 'Start and grow a freelance career', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['career', 'freelance']],
            ],
            'Life Skills' => [
                ['title' => 'Financial Literacy Basics', 'summary' => 'Budgeting and money management', 'level' => 'beginner', 'duration' => 75, 'tags' => ['life-skills', 'finance']],
                ['title' => 'Time Management Strategies', 'summary' => 'Maximize productivity effectively', 'level' => 'beginner', 'duration' => 45, 'tags' => ['life-skills', 'productivity']],
                ['title' => 'Adaptive Cooking Techniques', 'summary' => 'Safe cooking with mobility limitations', 'level' => 'beginner', 'duration' => 60, 'tags' => ['life-skills', 'cooking']],
                ['title' => 'Smart Home for Accessibility', 'summary' => 'Automate your home for independence', 'level' => 'intermediate', 'duration' => 80, 'tags' => ['life-skills', 'smart-home']],
                ['title' => 'Public Transportation Navigation', 'summary' => 'Navigate transit with mobility aids', 'level' => 'beginner', 'duration' => 40, 'tags' => ['life-skills', 'transportation']],
                ['title' => 'Online Shopping Accessibility', 'summary' => 'Shop online with assistive technology', 'level' => 'beginner', 'duration' => 30, 'tags' => ['life-skills', 'shopping']],
                ['title' => 'Benefits and Rights Overview', 'summary' => 'Understand disability benefits', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['life-skills', 'benefits']],
                ['title' => 'Housing Accessibility Guide', 'summary' => 'Find and adapt accessible housing', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['life-skills', 'housing']],
                ['title' => 'Travel Planning for Disability', 'summary' => 'Plan accessible trips with confidence', 'level' => 'intermediate', 'duration' => 60, 'tags' => ['life-skills', 'travel']],
                ['title' => 'Adaptive Fitness Routines', 'summary' => 'Exercise adapted to your abilities', 'level' => 'beginner', 'duration' => 50, 'tags' => ['life-skills', 'fitness']],
            ],
            'Health & Wellness' => [
                ['title' => 'Stress Management Techniques', 'summary' => 'Cope with stress effectively', 'level' => 'beginner', 'duration' => 45, 'tags' => ['health', 'mental-health']],
                ['title' => 'Mindfulness Meditation', 'summary' => 'Practice mindfulness for wellbeing', 'level' => 'beginner', 'duration' => 40, 'tags' => ['health', 'meditation']],
                ['title' => 'Chronic Pain Management', 'summary' => 'Strategies for managing chronic pain', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['health', 'pain-management']],
                ['title' => 'Sleep Hygiene Improvement', 'summary' => 'Better sleep for better health', 'level' => 'beginner', 'duration' => 35, 'tags' => ['health', 'sleep']],
                ['title' => 'Nutrition for Wellness', 'summary' => 'Healthy eating fundamentals', 'level' => 'beginner', 'duration' => 60, 'tags' => ['health', 'nutrition']],
                ['title' => 'Yoga for Wheelchair Users', 'summary' => 'Seated yoga for flexibility', 'level' => 'beginner', 'duration' => 45, 'tags' => ['health', 'yoga']],
                ['title' => 'Managing Fatigue', 'summary' => 'Strategies for chronic fatigue', 'level' => 'intermediate', 'duration' => 55, 'tags' => ['health', 'fatigue']],
                ['title' => 'Caregiver Self-Care', 'summary' => 'Wellness tips for caregivers', 'level' => 'beginner', 'duration' => 50, 'tags' => ['health', 'caregiving']],
                ['title' => 'Emotional Resilience Building', 'summary' => 'Develop emotional strength', 'level' => 'intermediate', 'duration' => 65, 'tags' => ['health', 'resilience']],
                ['title' => 'Anxiety Management Skills', 'summary' => 'Practical anxiety coping strategies', 'level' => 'beginner', 'duration' => 55, 'tags' => ['health', 'anxiety']],
            ],
            'Communication' => [
                ['title' => 'American Sign Language 101', 'summary' => 'Basic ASL vocabulary and phrases', 'level' => 'beginner', 'duration' => 90, 'tags' => ['communication', 'asl']],
                ['title' => 'ASL Intermediate', 'summary' => 'Expand your ASL vocabulary', 'level' => 'intermediate', 'duration' => 120, 'tags' => ['communication', 'asl']],
                ['title' => 'Lip Reading Fundamentals', 'summary' => 'Understand speech visually', 'level' => 'beginner', 'duration' => 75, 'tags' => ['communication', 'deaf-hoh']],
                ['title' => 'AAC Device Training', 'summary' => 'Using augmentative communication', 'level' => 'beginner', 'duration' => 60, 'tags' => ['communication', 'aac']],
                ['title' => 'Text-to-Speech Mastery', 'summary' => 'Effective TTS tool usage', 'level' => 'beginner', 'duration' => 35, 'tags' => ['communication', 'tts']],
                ['title' => 'Public Speaking Confidence', 'summary' => 'Present with confidence', 'level' => 'intermediate', 'duration' => 80, 'tags' => ['communication', 'speaking']],
                ['title' => 'Written Communication Skills', 'summary' => 'Write clearly and effectively', 'level' => 'beginner', 'duration' => 55, 'tags' => ['communication', 'writing']],
                ['title' => 'Virtual Meeting Accessibility', 'summary' => 'Participate in online meetings', 'level' => 'beginner', 'duration' => 40, 'tags' => ['communication', 'virtual']],
                ['title' => 'Assertive Communication', 'summary' => 'Communicate needs assertively', 'level' => 'intermediate', 'duration' => 50, 'tags' => ['communication', 'assertiveness']],
                ['title' => 'Social Skills Development', 'summary' => 'Build social connections', 'level' => 'beginner', 'duration' => 65, 'tags' => ['communication', 'social']],
            ],
            'Creative Arts' => [
                ['title' => 'Digital Art for Beginners', 'summary' => 'Create art with digital tools', 'level' => 'beginner', 'duration' => 90, 'tags' => ['creative', 'art']],
                ['title' => 'Photography Fundamentals', 'summary' => 'Capture great photos', 'level' => 'beginner', 'duration' => 75, 'tags' => ['creative', 'photography']],
                ['title' => 'Creative Writing Workshop', 'summary' => 'Express yourself through writing', 'level' => 'beginner', 'duration' => 80, 'tags' => ['creative', 'writing']],
                ['title' => 'Music Production Basics', 'summary' => 'Create music with software', 'level' => 'intermediate', 'duration' => 120, 'tags' => ['creative', 'music']],
                ['title' => 'Video Editing Fundamentals', 'summary' => 'Edit videos like a pro', 'level' => 'intermediate', 'duration' => 100, 'tags' => ['creative', 'video']],
                ['title' => 'Podcast Creation Guide', 'summary' => 'Start your own podcast', 'level' => 'beginner', 'duration' => 70, 'tags' => ['creative', 'podcast']],
                ['title' => 'Graphic Design Essentials', 'summary' => 'Design basics with Canva', 'level' => 'beginner', 'duration' => 65, 'tags' => ['creative', 'design']],
                ['title' => 'Adaptive Art Techniques', 'summary' => 'Art creation with adaptations', 'level' => 'beginner', 'duration' => 55, 'tags' => ['creative', 'adaptive']],
                ['title' => 'Blogging for Impact', 'summary' => 'Share your story online', 'level' => 'beginner', 'duration' => 60, 'tags' => ['creative', 'blogging']],
                ['title' => 'Animation Introduction', 'summary' => 'Create simple animations', 'level' => 'intermediate', 'duration' => 95, 'tags' => ['creative', 'animation']],
            ],
            'Entrepreneurship' => [
                ['title' => 'Starting a Small Business', 'summary' => 'Launch your business idea', 'level' => 'intermediate', 'duration' => 120, 'tags' => ['business', 'startup']],
                ['title' => 'Business Plan Development', 'summary' => 'Create a winning business plan', 'level' => 'intermediate', 'duration' => 90, 'tags' => ['business', 'planning']],
                ['title' => 'E-commerce Setup Guide', 'summary' => 'Sell products online', 'level' => 'beginner', 'duration' => 80, 'tags' => ['business', 'ecommerce']],
                ['title' => 'Social Media Marketing', 'summary' => 'Market your business socially', 'level' => 'beginner', 'duration' => 70, 'tags' => ['business', 'marketing']],
                ['title' => 'Disability-Owned Business', 'summary' => 'Certifications and opportunities', 'level' => 'intermediate', 'duration' => 55, 'tags' => ['business', 'certification']],
                ['title' => 'Pricing Strategies', 'summary' => 'Price your products right', 'level' => 'intermediate', 'duration' => 45, 'tags' => ['business', 'pricing']],
                ['title' => 'Customer Service Excellence', 'summary' => 'Deliver exceptional service', 'level' => 'beginner', 'duration' => 50, 'tags' => ['business', 'service']],
                ['title' => 'Accessible Business Practices', 'summary' => 'Make your business accessible', 'level' => 'intermediate', 'duration' => 65, 'tags' => ['business', 'accessibility']],
                ['title' => 'Funding Your Business', 'summary' => 'Find grants and investors', 'level' => 'advanced', 'duration' => 85, 'tags' => ['business', 'funding']],
                ['title' => 'Legal Basics for Entrepreneurs', 'summary' => 'Legal essentials for business', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['business', 'legal']],
            ],
            'Personal Development' => [
                ['title' => 'Goal Setting Mastery', 'summary' => 'Set and achieve meaningful goals', 'level' => 'beginner', 'duration' => 45, 'tags' => ['personal', 'goals']],
                ['title' => 'Building Self-Confidence', 'summary' => 'Develop lasting confidence', 'level' => 'beginner', 'duration' => 55, 'tags' => ['personal', 'confidence']],
                ['title' => 'Habit Formation Science', 'summary' => 'Build positive habits that stick', 'level' => 'intermediate', 'duration' => 60, 'tags' => ['personal', 'habits']],
                ['title' => 'Overcoming Imposter Syndrome', 'summary' => 'Recognize and overcome self-doubt', 'level' => 'intermediate', 'duration' => 50, 'tags' => ['personal', 'mindset']],
                ['title' => 'Work-Life Balance', 'summary' => 'Find balance in daily life', 'level' => 'beginner', 'duration' => 40, 'tags' => ['personal', 'balance']],
                ['title' => 'Boundary Setting Skills', 'summary' => 'Set healthy boundaries', 'level' => 'intermediate', 'duration' => 55, 'tags' => ['personal', 'boundaries']],
                ['title' => 'Decision Making Framework', 'summary' => 'Make better decisions', 'level' => 'intermediate', 'duration' => 45, 'tags' => ['personal', 'decisions']],
                ['title' => 'Embracing Change', 'summary' => 'Adapt to life transitions', 'level' => 'beginner', 'duration' => 50, 'tags' => ['personal', 'change']],
                ['title' => 'Gratitude Practice', 'summary' => 'Cultivate gratitude daily', 'level' => 'beginner', 'duration' => 30, 'tags' => ['personal', 'gratitude']],
                ['title' => 'Self-Compassion Skills', 'summary' => 'Be kind to yourself', 'level' => 'beginner', 'duration' => 45, 'tags' => ['personal', 'compassion']],
            ],
            'Advocacy & Rights' => [
                ['title' => 'ADA Rights Overview', 'summary' => 'Know your rights under ADA', 'level' => 'beginner', 'duration' => 70, 'tags' => ['advocacy', 'rights']],
                ['title' => 'Disability Advocacy 101', 'summary' => 'Become an effective advocate', 'level' => 'beginner', 'duration' => 60, 'tags' => ['advocacy', 'activism']],
                ['title' => 'Filing Accessibility Complaints', 'summary' => 'Report accessibility barriers', 'level' => 'intermediate', 'duration' => 55, 'tags' => ['advocacy', 'complaints']],
                ['title' => 'Workplace Accommodation Rights', 'summary' => 'Request accommodations legally', 'level' => 'intermediate', 'duration' => 65, 'tags' => ['advocacy', 'workplace']],
                ['title' => 'Education Rights (IDEA)', 'summary' => 'Educational rights for disabilities', 'level' => 'intermediate', 'duration' => 75, 'tags' => ['advocacy', 'education']],
                ['title' => 'Healthcare Advocacy', 'summary' => 'Advocate in healthcare settings', 'level' => 'intermediate', 'duration' => 60, 'tags' => ['advocacy', 'healthcare']],
                ['title' => 'Community Organizing', 'summary' => 'Build disability advocacy groups', 'level' => 'advanced', 'duration' => 85, 'tags' => ['advocacy', 'organizing']],
                ['title' => 'Media Representation', 'summary' => 'Improve disability in media', 'level' => 'intermediate', 'duration' => 50, 'tags' => ['advocacy', 'media']],
                ['title' => 'Voting Accessibility', 'summary' => 'Exercise voting rights accessibly', 'level' => 'beginner', 'duration' => 40, 'tags' => ['advocacy', 'voting']],
                ['title' => 'International Disability Rights', 'summary' => 'Global disability frameworks', 'level' => 'advanced', 'duration' => 80, 'tags' => ['advocacy', 'international']],
            ],
        ];

        foreach ($categories as $category => $categoryCourses) {
            foreach ($categoryCourses as $course) {
                $courses[] = [
                    'title' => $course['title'],
                    'summary' => $course['summary'],
                    'category' => $category,
                    'level' => $course['level'],
                    'duration' => $course['duration'],
                    'tags' => $course['tags'],
                    'description' => $this->generateCourseDescription($course['title'], $course['summary'], $category),
                ];
            }
        }

        return $courses;
    }

    private function generateCourseDescription($title, $summary, $category): string
    {
        return "## Course Overview

**{$summary}**

This comprehensive course in the **{$category}** category will guide you through essential concepts and practical skills. Whether you're just starting or looking to enhance your abilities, this course provides accessible, step-by-step instruction.

## What You'll Learn

- Understand core principles and best practices
- Apply practical techniques to real-world situations
- Build confidence through hands-on exercises
- Develop skills you can use immediately
- Connect concepts to your personal goals

## Course Features

- **Accessible Content**: All materials are screen reader compatible
- **Self-Paced Learning**: Progress at your own speed
- **Practical Exercises**: Apply what you learn
- **Certificate of Completion**: Demonstrate your achievement
- **Community Support**: Connect with fellow learners

## Who This Course Is For

- Individuals seeking to develop new skills
- Those looking to improve existing abilities
- Anyone interested in accessible learning
- Learners who prefer self-paced instruction

## Requirements

- No prior experience needed
- Basic computer literacy
- Willingness to learn and practice

## Start Your Learning Journey

This course is designed with accessibility in mind, ensuring everyone can participate fully. Join us and take the next step in your personal and professional development.";
    }

    private function seedMatrimonyProfiles(): void
    {
        $this->command->info('💑 Creating 20 matrimony profiles...');
        
        $occupations = ['Software Engineer', 'Teacher', 'Healthcare Worker', 'Artist', 'Writer', 'Entrepreneur', 'Consultant', 'Designer', 'Analyst', 'Manager'];
        $education = ['High School', 'Bachelor\'s Degree', 'Master\'s Degree', 'PhD', 'Associate Degree', 'Professional Certification'];
        $religions = ['Christian', 'Muslim', 'Hindu', 'Buddhist', 'Jewish', 'Other', 'Not Religious'];
        $statuses = ['Never Married', 'Divorced', 'Widowed'];
        $hobbies = [
            ['Reading', 'Writing', 'Music'],
            ['Cooking', 'Travel', 'Photography'],
            ['Gaming', 'Movies', 'Art'],
            ['Sports', 'Fitness', 'Yoga'],
            ['Technology', 'Learning', 'Volunteering'],
        ];

        // Get all users except admin
        $users = User::where('role', '!=', 'admin')->get();
        
        foreach ($users as $index => $user) {
            $gender = $index % 2 === 0 ? 'male' : 'female';
            
            MatrimonyProfile::create([
                'user_id' => $user->id,
                'gender' => $gender,
                'age' => rand(22, 45),
                'bio' => "Hello! I'm {$user->name}. I'm looking for a meaningful connection with someone who shares my values and interests. I believe in kindness, mutual respect, and building a supportive partnership.",
                'occupation' => $occupations[$index % count($occupations)],
                'education' => $education[$index % count($education)],
                'marital_status' => $statuses[$index % count($statuses)],
                'religion' => $religions[$index % count($religions)],
                'partner_preferences' => 'Looking for someone kind, understanding, and supportive. Values open communication and shares similar life goals.',
                'hobbies' => $hobbies[$index % count($hobbies)],
                'privacy_level' => ['public', 'members_only', 'matches_only'][$index % 3],
            ]);
        }
        
        $this->command->info('   Created ' . $users->count() . ' matrimony profiles');
    }

    private function seedEvents(): void
    {
        $this->command->info('📅 Creating 20 events...');
        
        $events = [
            ['title' => 'Accessibility in Tech Workshop', 'virtual' => true],
            ['title' => 'Disability Rights Awareness Meetup', 'virtual' => false],
            ['title' => 'Assistive Technology Expo', 'virtual' => false],
            ['title' => 'Mental Health Support Group', 'virtual' => true],
            ['title' => 'Career Fair for PWD', 'virtual' => false],
            ['title' => 'Adaptive Sports Day', 'virtual' => false],
            ['title' => 'Caregiver Support Network', 'virtual' => true],
            ['title' => 'Sign Language Social', 'virtual' => false],
            ['title' => 'Financial Planning Webinar', 'virtual' => true],
            ['title' => 'Art Therapy Workshop', 'virtual' => false],
            ['title' => 'Tech Skills Bootcamp', 'virtual' => true],
            ['title' => 'Community Potluck Gathering', 'virtual' => false],
            ['title' => 'Advocacy Training Session', 'virtual' => true],
            ['title' => 'Inclusive Gaming Night', 'virtual' => true],
            ['title' => 'Health & Wellness Fair', 'virtual' => false],
            ['title' => 'Resume Writing Workshop', 'virtual' => true],
            ['title' => 'Meditation & Mindfulness', 'virtual' => true],
            ['title' => 'Parent Support Circle', 'virtual' => true],
            ['title' => 'Accessible Travel Planning', 'virtual' => true],
            ['title' => 'Annual Community Celebration', 'virtual' => false],
        ];

        $locations = ['Community Center, 123 Main St', 'City Hall, 456 Oak Ave', 'Public Library, 789 Park Blvd', 'Recreation Center, 321 Elm St', 'Convention Center, 654 Pine Rd'];
        
        // Get an organizer
        $organizers = User::whereIn('role', ['admin', 'volunteer'])->get();
        
        foreach ($events as $index => $event) {
            $organizer = $organizers[$index % count($organizers)];
            $eventType = $event['virtual'] ? 'online' : 'offline';
            
            CommunityEvent::create([
                'organizer_id' => $organizer->id,
                'title' => $event['title'],
                'description' => $this->generateEventDescription($event['title'], $eventType),
                'event_date' => Carbon::now()->addDays(rand(7, 90))->setHour(rand(9, 18))->setMinute(0),
                'location' => $event['virtual'] ? 'Online' : $locations[$index % count($locations)],
                'type' => $eventType,
                'meeting_link' => $event['virtual'] ? 'https://meet.ablelink.com/' . Str::random(10) : null,
            ]);
        }
        
        $this->command->info('   Created 20 community events');
    }

    private function generateEventDescription($title, $type): string
    {
        return "## About This Event

Join us for **{$title}**! This {$type} event brings our community together for learning, connection, and support.

## What to Expect

- Engaging activities and discussions
- Opportunity to connect with others
- Accessible format for all participants
- Supportive and inclusive environment

## Accessibility Information

- All venues are wheelchair accessible
- Sign language interpretation available upon request
- Captioning provided for virtual events
- Materials available in alternative formats

## How to Participate

Register through AbleLink to receive event updates and connection details. We look forward to seeing you there!

*Please contact us if you need any accommodations.*";
    }

    private function seedAssistanceRequests(): void
    {
        $this->command->info('🆘 Creating assistance requests...');
        
        // Valid types: transportation, companionship, errands, technical_support, medical_assistance, other
        $types = ['transportation', 'companionship', 'errands', 'technical_support', 'medical_assistance', 'other'];
        $urgencies = ['low', 'medium', 'high', 'emergency'];
        
        $requestTemplates = [
            'transportation' => ['title' => 'Need ride to appointment', 'desc' => 'Looking for assistance getting to my medical appointment.'],
            'companionship' => ['title' => 'Companionship visit', 'desc' => 'Looking for friendly companionship and conversation.'],
            'errands' => ['title' => 'Grocery shopping help', 'desc' => 'Need help with weekly grocery shopping and errands.'],
            'technical_support' => ['title' => 'Tech support needed', 'desc' => 'Need help setting up and learning to use new assistive technology.'],
            'medical_assistance' => ['title' => 'Accompaniment to doctor', 'desc' => 'Would appreciate someone to accompany me to my doctor visit.'],
            'other' => ['title' => 'Help with household tasks', 'desc' => 'Need assistance with some household chores and organization.'],
        ];

        // Get disabled and caregiver users
        $disabledUsers = User::where('role', 'disabled')->get();
        $caregivers = User::where('role', 'caregiver')->get();

        // Create requests for disabled users
        foreach ($disabledUsers as $index => $user) {
            $type = $types[$index % count($types)];
            $template = $requestTemplates[$type];
            
            AssistanceRequest::create([
                'user_id' => $user->id,
                'title' => $template['title'],
                'description' => $template['desc'] . ' I am flexible with timing and appreciate any help.',
                'type' => $type,
                'urgency' => $urgencies[$index % count($urgencies)],
                'location' => 'Downtown area',
                'preferred_date_time' => Carbon::now()->addDays(rand(1, 14)),
                'status' => 'pending',
                'special_requirements' => 'Wheelchair accessible vehicle preferred.',
            ]);
        }

        // Create requests for caregivers (respite care)
        foreach ($caregivers as $index => $user) {
            AssistanceRequest::create([
                'user_id' => $user->id,
                'title' => 'Respite care support',
                'description' => 'Looking for respite care support to take a short break. Need someone experienced with care.',
                'type' => 'companionship',
                'urgency' => $urgencies[$index % count($urgencies)],
                'location' => 'Home visit',
                'preferred_date_time' => Carbon::now()->addDays(rand(1, 14)),
                'status' => 'pending',
                'special_requirements' => 'Experience with caregiving preferred.',
            ]);
        }
        
        $this->command->info('   Created ' . ($disabledUsers->count() + $caregivers->count()) . ' assistance requests');
    }

    private function seedAidPrograms(): void
    {
        $this->command->info('🏛️ Creating 10 PWA Aid Programs...');
        
        $programs = [
            [
                'title' => 'Disability Employment Initiative',
                'agency' => 'Department of Labor',
                'category' => 'Employment',
                'summary' => 'Job placement and training programs for people with disabilities.',
                'tags' => ['employment', 'training', 'job-placement'],
            ],
            [
                'title' => 'Accessible Housing Assistance',
                'agency' => 'Housing Authority',
                'category' => 'Housing',
                'summary' => 'Rental assistance and home modifications for accessible living.',
                'tags' => ['housing', 'accessibility', 'modifications'],
            ],
            [
                'title' => 'Assistive Technology Grant Program',
                'agency' => 'Department of Health',
                'category' => 'Technology',
                'summary' => 'Funding for assistive devices and technology equipment.',
                'tags' => ['technology', 'devices', 'funding'],
            ],
            [
                'title' => 'Healthcare Access Initiative',
                'agency' => 'Healthcare Services',
                'category' => 'Healthcare',
                'summary' => 'Subsidized healthcare and specialized medical services.',
                'tags' => ['healthcare', 'medical', 'insurance'],
            ],
            [
                'title' => 'Education Support Program',
                'agency' => 'Department of Education',
                'category' => 'Education',
                'summary' => 'Educational accommodations and learning support services.',
                'tags' => ['education', 'learning', 'accommodations'],
            ],
            [
                'title' => 'Transportation Assistance Program',
                'agency' => 'Transit Authority',
                'category' => 'Transportation',
                'summary' => 'Accessible transportation services and fare subsidies.',
                'tags' => ['transportation', 'mobility', 'transit'],
            ],
            [
                'title' => 'Caregiver Support Services',
                'agency' => 'Family Services',
                'category' => 'Caregiving',
                'summary' => 'Respite care and support resources for caregivers.',
                'tags' => ['caregiving', 'respite', 'support'],
            ],
            [
                'title' => 'Disability Income Support',
                'agency' => 'Social Security Administration',
                'category' => 'Financial',
                'summary' => 'Monthly income assistance for eligible individuals.',
                'tags' => ['income', 'financial', 'benefits'],
            ],
            [
                'title' => 'Vocational Rehabilitation Services',
                'agency' => 'Rehabilitation Services',
                'category' => 'Employment',
                'summary' => 'Career counseling and job training for employment success.',
                'tags' => ['vocational', 'rehabilitation', 'career'],
            ],
            [
                'title' => 'Mental Health Support Program',
                'agency' => 'Mental Health Services',
                'category' => 'Healthcare',
                'summary' => 'Counseling and mental health treatment services.',
                'tags' => ['mental-health', 'counseling', 'therapy'],
            ],
        ];

        foreach ($programs as $program) {
            $slug = Str::slug($program['title']);
            
            DB::table('aid_programs')->insert([
                'slug' => $slug,
                'title' => $program['title'],
                'agency' => $program['agency'],
                'category' => $program['category'],
                'region' => 'National',
                'summary' => $program['summary'],
                'eligibility' => $this->generateEligibility($program['category']),
                'benefits' => $this->generateBenefits($program['category']),
                'how_to_apply' => $this->generateHowToApply(),
                'application_url' => 'https://apply.gov/' . $slug,
                'contact_phone' => '+1-800-' . rand(100, 999) . '-' . rand(1000, 9999),
                'contact_email' => strtolower(str_replace(' ', '', $program['agency'])) . '@gov.example.com',
                'is_active' => true,
                'tags' => json_encode($program['tags']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('   Created 10 aid programs');
    }

    private function generateEligibility($category): string
    {
        return "## Eligibility Requirements

To qualify for this {$category} program, applicants must meet the following criteria:

### Basic Requirements
- Must be a resident of the service area
- Must have a documented disability or qualifying condition
- Meet income guidelines (if applicable)

### Documentation Needed
- Valid government-issued ID
- Proof of disability (medical documentation)
- Proof of residency
- Income verification (if required)

### Additional Criteria
- Specific eligibility may vary by program component
- Some services may have waiting lists
- Reassessment may be required annually

*Contact our office for personalized eligibility determination.*";
    }

    private function generateBenefits($category): string
    {
        return "## Program Benefits

This {$category} program provides the following support:

### Primary Benefits
- Direct services and support
- Financial assistance (where applicable)
- Access to specialized resources
- Case management and coordination

### Additional Support
- Information and referral services
- Advocacy assistance
- Community connections
- Ongoing support and follow-up

### Long-term Value
- Increased independence
- Improved quality of life
- Connection to community resources
- Sustainable support pathways

*Benefits are subject to availability and individual assessment.*";
    }

    private function generateHowToApply(): string
    {
        return "## How to Apply

### Step 1: Gather Documents
Collect required documentation including ID, proof of disability, and income verification.

### Step 2: Submit Application
Apply online through our portal or submit a paper application at our office.

### Step 3: Interview
Complete an intake interview with our staff (in-person, phone, or video).

### Step 4: Assessment
Undergo eligibility determination and needs assessment.

### Step 5: Enrollment
Upon approval, receive enrollment confirmation and begin services.

### Need Help?
Contact our helpline for application assistance. Language interpretation and accessibility accommodations are available.

*Processing time is typically 2-4 weeks.*";
    }

    private function seedForumPosts(): void
    {
        $this->command->info('💬 Creating 50 forum posts...');
        
        $categories = ['General', 'Accessibility', 'Employment', 'Health', 'Technology'];
        
        $forumPosts = [
            // General Category
            ['category' => 'General', 'title' => 'Welcome to the AbleLink Community Forum!', 'body' => $this->getWelcomePostBody()],
            ['category' => 'General', 'title' => 'Tips for New Members: Getting Started', 'body' => $this->getGettingStartedBody()],
            ['category' => 'General', 'title' => 'Share Your Story: How Has AbleLink Helped You?', 'body' => "## Share Your Story\n\nWe'd love to hear how AbleLink has made a difference in your life!\n\n### Discussion Points\n- How did you discover AbleLink?\n- What features have been most helpful?\n- What goals have you achieved?\n\n*Your story could inspire someone else in our community!*"],
            ['category' => 'General', 'title' => 'Community Guidelines and Best Practices', 'body' => "## Community Guidelines\n\n### Be Respectful\n- Treat everyone with dignity\n- No discrimination or harassment\n- Use inclusive language\n\n### Stay Supportive\n- Offer constructive advice\n- Celebrate others' achievements\n- Report inappropriate content\n\n### Protect Privacy\n- Don't share others' personal info\n- Ask before sharing experiences\n\n*Together we create a welcoming space for all.*"],
            ['category' => 'General', 'title' => 'Monthly Check-in: How Are You Doing?', 'body' => "## Monthly Check-in Thread\n\n### How's everyone doing this month?\n\nShare your wins, challenges, or just say hello!\n\n- 🌟 **Wins**: What went well?\n- 💪 **Challenges**: What are you working through?\n- 🤝 **Requests**: Need support or advice?\n\n*Remember: It's okay to not be okay. We're here for you.*"],
            ['category' => 'General', 'title' => 'Favorite Podcasts and YouTube Channels', 'body' => "## Recommendations Thread\n\n### Share your favorite accessible content!\n\n**My recommendations:**\n\n1. **Disability After Dark** - Great discussions\n2. **The Accessible Stall** - Humor and advocacy\n3. **Sitting Pretty** - Book club vibes\n\n*What are you listening to or watching?*"],
            ['category' => 'General', 'title' => 'Dealing with Isolation: Finding Connection', 'body' => "## Finding Connection\n\n### Feeling isolated? You're not alone.\n\n**Strategies that help me:**\n\n- Virtual coffee chats with friends\n- Online gaming communities\n- This forum!\n- Scheduled video calls\n\n### What works for you?\n\n*Let's share ideas for staying connected.*"],
            ['category' => 'General', 'title' => 'Book Club: February Reading Suggestions', 'body' => "## 📚 February Book Club\n\n### This Month's Pick\n\n**'Disability Visibility' edited by Alice Wong**\n\nA collection of first-person essays by disabled writers.\n\n### Discussion Questions\n1. Which essay resonated most?\n2. How did it change your perspective?\n3. What would you add?\n\n*Join us for discussion on the last Friday!*"],
            ['category' => 'General', 'title' => 'Advocacy Success Stories', 'body' => "## Advocacy Wins 🎉\n\n### Share your advocacy successes!\n\n**My recent win:**\nGot my workplace to install automatic doors after 6 months of advocacy!\n\n### Tips that worked:\n- Document everything\n- Know your rights\n- Find allies\n- Stay persistent\n\n*What's your advocacy win?*"],
            ['category' => 'General', 'title' => 'Weekend Plans Thread', 'body' => "## What's Everyone Up To This Weekend?\n\n### My Plans\n- Trying a new accessible hiking trail\n- Movie marathon at home\n- Meal prepping for the week\n\n### Share yours!\nLooking for inspiration and maybe some virtual company!\n\n*Have a great weekend, everyone!*"],
            
            // Accessibility Category
            ['category' => 'Accessibility', 'title' => 'Best Screen Readers in 2026: A Comparison', 'body' => "## Screen Reader Comparison 2026\n\n### Top Options\n\n| Reader | Platform | Price | Rating |\n|--------|----------|-------|--------|\n| JAWS | Windows | \$1000/yr | ⭐⭐⭐⭐⭐ |\n| NVDA | Windows | Free | ⭐⭐⭐⭐ |\n| VoiceOver | Mac/iOS | Free | ⭐⭐⭐⭐⭐ |\n| TalkBack | Android | Free | ⭐⭐⭐⭐ |\n\n### My Experience\nI've used NVDA for 5 years and love it!\n\n*What's your go-to screen reader?*"],
            ['category' => 'Accessibility', 'title' => 'Keyboard Shortcuts That Changed My Life', 'body' => "## Essential Keyboard Shortcuts\n\n### Windows\n- `Win + U`: Accessibility settings\n- `Ctrl + Win + O`: On-screen keyboard\n- `Win + +`: Magnifier\n\n### Browser\n- `Tab`: Navigate links\n- `Ctrl + L`: Address bar\n- `Alt + Left`: Back\n\n### Pro Tip\nCreate custom shortcuts for frequent tasks!\n\n*Share your favorite shortcuts below!*"],
            ['category' => 'Accessibility', 'title' => 'Making Your Home More Accessible', 'body' => "## Home Accessibility Upgrades\n\n### Budget-Friendly Options\n1. **Lever door handles** - \$15-30 each\n2. **Grab bars** - \$20-50\n3. **Smart plugs** - \$25 for pack\n4. **Motion lights** - \$15-20\n\n### Bigger Projects\n- Ramp installation\n- Walk-in shower\n- Stairlift\n\n*What modifications helped you most?*"],
            ['category' => 'Accessibility', 'title' => 'Traveling with Accessibility Needs', 'body' => "## Travel Tips Thread\n\n### Before You Go\n- Call ahead about accommodations\n- Research accessible attractions\n- Pack extra supplies\n- Know your rights (Air Carrier Access Act)\n\n### At the Airport\n- Request wheelchair assistance early\n- TSA Cares program is helpful\n- Arrive extra early\n\n### Hotel Tips\n- Confirm accessible room features\n- Take photos on arrival\n\n*Where have you traveled? Tips welcome!*"],
            ['category' => 'Accessibility', 'title' => 'Voice Control: Dragon vs Voice Access', 'body' => "## Voice Control Showdown\n\n### Dragon NaturallySpeaking\n**Pros:**\n- Very accurate\n- Great for dictation\n**Cons:**\n- Expensive (\$150+)\n- Windows only\n\n### Windows Voice Access\n**Pros:**\n- Free\n- Good for navigation\n**Cons:**\n- Less accurate\n- Limited dictation\n\n*Which do you prefer?*"],
            ['category' => 'Accessibility', 'title' => 'Accessible Gaming: Controller Recommendations', 'body' => "## Accessible Gaming Controllers\n\n### Top Picks\n\n1. **Xbox Adaptive Controller** - \$100\n   - Highly customizable\n   - Works with external switches\n\n2. **Logitech Adaptive Gaming Kit** - \$100\n   - Great with Xbox AC\n\n3. **QuadStick** - \$400+\n   - Mouth-operated\n\n### Games with Great Accessibility\n- The Last of Us Part II\n- Forza Horizon 5\n- Spider-Man 2\n\n*What's your setup?*"],
            ['category' => 'Accessibility', 'title' => 'Caption Quality: Which Services Get It Right?', 'body' => "## Captioning Services Ranked\n\n### Streaming Services\n1. 🥇 **Netflix** - Excellent, customizable\n2. 🥈 **Disney+** - Good quality\n3. 🥉 **Amazon Prime** - Inconsistent\n\n### Live Captioning\n- Google Meet: Very good\n- Zoom: Good with paid plans\n- Teams: Improving\n\n### Tips\n- Always check caption settings\n- Report errors to improve quality\n\n*Your experiences?*"],
            ['category' => 'Accessibility', 'title' => 'Braille Displays: Worth the Investment?', 'body' => "## Braille Display Discussion\n\n### Is it worth \$3,000+?\n\n**My perspective:**\nAbsolutely, for daily use. I use mine for:\n- Reading books\n- Coding (!)\n- Email\n- Taking notes\n\n### Budget Options\n- Orbit Reader 20: ~\$500\n- Used BrailleNote\n\n### Funding Sources\n- VR/DVR services\n- Nonprofits\n- Payment plans\n\n*Do you use a braille display?*"],
            ['category' => 'Accessibility', 'title' => 'Advocating for Accessibility at Work', 'body' => "## Workplace Accessibility Advocacy\n\n### Steps to Request Accommodations\n\n1. **Document your needs**\n2. **Know your rights** (ADA)\n3. **Submit formal request** to HR\n4. **Propose solutions**\n5. **Follow up in writing**\n\n### Common Accommodations\n- Screen reader software\n- Ergonomic equipment\n- Flexible schedule\n- Remote work\n\n*What accommodations do you use?*"],
            ['category' => 'Accessibility', 'title' => 'Best Apps for Visual Impairment', 'body' => "## Must-Have Apps\n\n### Navigation\n- **Soundscape** by Microsoft\n- **BlindSquare** - Indoor/outdoor\n- **Seeing AI** - Object recognition\n\n### Daily Life\n- **Be My Eyes** - Video assistance\n- **KNFB Reader** - Document reading\n- **Envision AI** - Smart glasses app\n\n### Money\n- **Cash Reader** - Identify bills\n\n*What apps can't you live without?*"],
            
            // Employment Category
            ['category' => 'Employment', 'title' => 'Resume Tips for Disclosing Disability', 'body' => "## To Disclose or Not?\n\n### It's Personal\nThere's no right answer. Consider:\n- Company culture\n- Role requirements\n- Your comfort level\n\n### If You Disclose\n- Focus on abilities\n- Mention relevant accommodations experience\n- Highlight problem-solving skills\n\n### Resume Tips\n- Lead with skills\n- Quantify achievements\n- Address gaps honestly (if asked)\n\n*What's your approach?*"],
            ['category' => 'Employment', 'title' => 'Remote Work: Blessing or Barrier?', 'body' => "## Remote Work Discussion\n\n### Benefits\n- No commute issues\n- Comfortable environment\n- Flexible scheduling\n- Control over accessibility\n\n### Challenges\n- Isolation\n- Communication barriers\n- Home office setup costs\n- Blurred boundaries\n\n### Tips\n- Set clear work hours\n- Create dedicated space\n- Stay connected virtually\n\n*Is remote work right for you?*"],
            ['category' => 'Employment', 'title' => 'Interview Accommodations: What to Ask For', 'body' => "## Interview Accommodations\n\n### Your Rights\nYou CAN request accommodations for interviews!\n\n### Common Requests\n- Extended time\n- Written questions in advance\n- Sign language interpreter\n- Accessible location\n- Virtual interview option\n\n### How to Ask\n> \"I'd like to request [accommodation] to ensure I can fully demonstrate my qualifications.\"\n\n*What accommodations have helped you?*"],
            ['category' => 'Employment', 'title' => 'Freelancing with a Disability', 'body' => "## Freelance Freedom\n\n### Why Freelancing Works\n- Set your own hours\n- Choose your projects\n- Work from anywhere\n- Control your environment\n\n### Getting Started\n1. Identify your skills\n2. Build a portfolio\n3. Set up on platforms (Upwork, Fiverr)\n4. Network online\n\n### Challenges\n- Inconsistent income\n- Self-marketing\n- No benefits\n\n*Any freelancers here? Tips?*"],
            ['category' => 'Employment', 'title' => 'Disability-Friendly Employers List', 'body' => "## Companies That Get It Right\n\n### Top Employers\n| Company | Industry | Notable |\n|---------|----------|---------|\n| Microsoft | Tech | Inclusive hiring |\n| Accenture | Consulting | Accessibility focus |\n| EY | Finance | Neuro-diverse programs |\n| Walgreens | Retail | Distribution centers |\n\n### How to Research\n- Check Disability:IN scores\n- Read Glassdoor reviews\n- Look for ERGs\n\n*Add your recommendations!*"],
            ['category' => 'Employment', 'title' => 'Explaining Employment Gaps', 'body' => "## Addressing Gaps\n\n### Honest Approaches\n\n**Option 1: Brief explanation**\n> \"I took time to address health needs and am now ready to contribute fully.\"\n\n**Option 2: Focus on growth**\n> \"During that time, I developed skills in [X] through online learning.\"\n\n**Option 3: Redirect**\n> \"I'm excited about this opportunity because...\"\n\n### Key Points\n- You don't owe details\n- Stay positive\n- Emphasize readiness\n\n*How have you handled this?*"],
            ['category' => 'Employment', 'title' => 'Negotiating Salary with Confidence', 'body' => "## Salary Negotiation\n\n### Know Your Worth\n- Research market rates\n- Factor in your experience\n- Don't undersell yourself\n\n### Scripts\n\n**Initial offer:**\n> \"Thank you for the offer. Based on my research and experience, I was expecting [X]. Can we discuss?\"\n\n**Accommodations cost:**\n> \"My accommodations don't affect my productivity or the budget.\"\n\n### Resources\n- Glassdoor\n- LinkedIn Salary\n- Industry surveys\n\n*Have you negotiated successfully?*"],
            ['category' => 'Employment', 'title' => 'Starting a Disability-Owned Business', 'body' => "## Entrepreneurship Thread\n\n### Why Start a Business?\n- Be your own boss\n- Create accessible workplace\n- Flexible schedule\n- Fill a market gap\n\n### Resources\n- SBA disability resources\n- SCORE mentorship\n- Disability-owned certification\n\n### Challenges\n- Healthcare access\n- Funding\n- Energy management\n\n*Any business owners here?*"],
            ['category' => 'Employment', 'title' => 'Vocational Rehabilitation: Is It Worth It?', 'body' => "## VR Services Discussion\n\n### What VR Provides\n- Career counseling\n- Job training\n- Assistive technology\n- Job placement\n- Education support\n\n### My Experience\n\u2705 Got my laptop\n\u2705 Paid for certifications\n\u2705 Job coaching\n\n### Tips\n- Be your own advocate\n- Document everything\n- Stay in communication\n- Know your rights\n\n*What's your VR experience?*"],
            ['category' => 'Employment', 'title' => 'Dealing with Workplace Discrimination', 'body' => "## Facing Discrimination\n\n### Warning Signs\n- Denied reasonable accommodations\n- Passed over for promotions\n- Hostile comments\n- Unequal treatment\n\n### Steps to Take\n1. Document incidents\n2. File internal complaint\n3. Contact EEOC\n4. Consult attorney\n\n### Resources\n- Job Accommodation Network\n- EEOC.gov\n- State civil rights agency\n\n*Solidarity and support here.*"],
            
            // Health Category
            ['category' => 'Health', 'title' => 'Managing Chronic Pain: What Works for You?', 'body' => "## Chronic Pain Management\n\n### Strategies That Help Me\n\n**Physical:**\n- Gentle stretching\n- Heat/cold therapy\n- TENS unit\n\n**Mental:**\n- Meditation apps\n- Pacing activities\n- Acceptance therapy\n\n**Medical:**\n- Working with pain specialist\n- Trying different approaches\n\n### Daily Tips\n- Don't push through\n- Rest before exhausted\n- Track triggers\n\n*What helps you manage pain?*"],
            ['category' => 'Health', 'title' => 'Finding Accessible Healthcare Providers', 'body' => "## Accessible Healthcare\n\n### What to Look For\n- Physical accessibility\n- Communication accommodations\n- Understanding of your condition\n- Patient-centered approach\n\n### Questions to Ask\n- \"Do you have accessible exam tables?\"\n- \"Can you provide materials in alternative formats?\"\n- \"What's your experience with [condition]?\"\n\n### Resources\n- DisabilityInfo.org\n- Local CILs\n- Patient reviews\n\n*Share your recommendations!*"],
            ['category' => 'Health', 'title' => 'Medication Management Apps', 'body' => "## Med Management\n\n### Top Apps\n\n**Medisafe** ⭐⭐⭐⭐⭐\n- Visual reminders\n- Family sharing\n- Refill alerts\n\n**CareZone**\n- Barcode scanning\n- Medication info\n\n**MyTherapy**\n- Mood tracking\n- Health journal\n\n### Accessibility\n- VoiceOver compatible\n- Large text options\n- Audio reminders\n\n*What do you use?*"],
            ['category' => 'Health', 'title' => 'Mental Health and Disability: Breaking the Stigma', 'body' => "## Mental Health Discussion\n\n### The Reality\nDisability + mental health challenges are common and valid.\n\n### Breaking Stigma\n- Talk openly\n- Seek support\n- It's not weakness\n- Treatment works\n\n### Resources\n- 988 Suicide Lifeline\n- NAMI\n- Disability-specific support groups\n- Telehealth options\n\n### Affirmation\nYou deserve mental wellness support. Period.\n\n*You're not alone.*"],
            ['category' => 'Health', 'title' => 'Exercise and Fitness: Adapted Workouts', 'body' => "## Adapted Fitness\n\n### Seated Workouts\n- Chair yoga\n- Seated strength training\n- Arm cycling\n\n### Low Impact\n- Swimming/aqua therapy\n- Recumbent bikes\n- Resistance bands\n\n### Apps & Videos\n- Seated Exercises (YouTube)\n- Adaptive Training Academy\n- Wheelchair Fitness Solution\n\n### Tips\n- Start slow\n- Listen to your body\n- Modify as needed\n\n*What's your routine?*"],
            ['category' => 'Health', 'title' => 'Sleep Issues: Tips for Better Rest', 'body' => "## Sleep Better\n\n### Common Challenges\n- Pain interference\n- Medication effects\n- Anxiety\n- Positioning\n\n### What Helps\n- Consistent schedule\n- Cool, dark room\n- White noise\n- Positioning pillows\n- Limiting screens\n\n### Tech Helpers\n- Sleep tracking apps\n- Smart pillows\n- Adjustable beds\n\n*Share your sleep tips!*"],
            ['category' => 'Health', 'title' => 'Nutrition and Energy Management', 'body' => "## Eating for Energy\n\n### General Tips\n- Small, frequent meals\n- Protein with every meal\n- Stay hydrated\n- Limit sugar crashes\n\n### Easy Meal Prep\n- One-pot meals\n- Freezer-friendly batches\n- Simple ingredients\n\n### Accommodations\n- Adaptive utensils\n- One-handed tools\n- Meal delivery services\n\n*What are your go-to easy meals?*"],
            ['category' => 'Health', 'title' => 'Caregiver Self-Care Thread', 'body' => "## Caregivers Need Care Too\n\n### You Matter\nYou can't pour from an empty cup.\n\n### Self-Care Ideas\n- 10-minute breaks\n- Ask for help\n- Respite services\n- Support groups\n- Personal time\n\n### Resources\n- Caregiver Action Network\n- Family Caregiver Alliance\n- Local respite programs\n\n### Affirmation\nTaking care of yourself isn't selfish.\n\n*Caregivers, how are YOU doing?*"],
            ['category' => 'Health', 'title' => 'Telehealth: The Good and the Frustrating', 'body' => "## Telehealth Experiences\n\n### Benefits\n✅ No transportation\n✅ Home comfort\n✅ Easier scheduling\n✅ Less exposure risk\n\n### Challenges\n❌ Tech issues\n❌ Limited exams\n❌ Insurance confusion\n❌ Screen fatigue\n\n### Tips\n- Test tech beforehand\n- Good lighting\n- Write questions down\n- Request accommodations\n\n*How has telehealth worked for you?*"],
            ['category' => 'Health', 'title' => 'Navigating Health Insurance', 'body' => "## Insurance Navigation\n\n### Key Terms\n- **Deductible**: Pay before insurance kicks in\n- **Copay**: Fixed cost per visit\n- **Coinsurance**: % you pay\n- **Out-of-pocket max**: Your cap\n\n### Tips\n- Appeal denials (often!)\n- Know what's covered\n- Use case managers\n- Document everything\n\n### Resources\n- Patient advocacy orgs\n- State insurance commissioner\n\n*What's your biggest insurance challenge?*"],
            
            // Technology Category
            ['category' => 'Technology', 'title' => 'Best Smartphones for Accessibility', 'body' => "## Smartphone Comparison\n\n### iPhone (iOS)\n**Pros:**\n- VoiceOver is excellent\n- Consistent experience\n- Strong app accessibility\n**Cons:**\n- Expensive\n- Less customizable\n\n### Android\n**Pros:**\n- More affordable options\n- Highly customizable\n- TalkBack improving\n**Cons:**\n- Inconsistent across devices\n\n*Team iPhone or Android?*"],
            ['category' => 'Technology', 'title' => 'Smart Home Devices for Independence', 'body' => "## Smart Home Setup\n\n### Essential Devices\n\n**Voice Assistants**\n- Amazon Echo Show (visual + voice)\n- Google Nest Hub\n\n**Smart Plugs**\n- Control anything with voice\n- Schedules and routines\n\n**Door Locks**\n- August, Schlage\n- No fumbling with keys\n\n**Cameras**\n- See who's at door\n- Two-way communication\n\n*What's in your smart home?*"],
            ['category' => 'Technology', 'title' => 'Coding with a Disability: Resources', 'body' => "## Accessible Coding\n\n### Screen Reader Friendly IDEs\n- VS Code (excellent)\n- IntelliJ with plugins\n- Eclipse\n\n### Learning Resources\n- freeCodeCamp (accessible)\n- The Odin Project\n- Codecademy\n\n### Communities\n- Blind Programmer Discord\n- Twitter/X #A11y community\n- GitHub accessibility projects\n\n### Tips\n- Use keyboard navigation\n- Custom themes help\n- CLI tools are friends\n\n*Coders, share your setup!*"],
            ['category' => 'Technology', 'title' => 'Wearable Tech for Health Monitoring', 'body' => "## Wearables Discussion\n\n### Health Monitoring\n\n**Apple Watch**\n- Fall detection\n- Heart monitoring\n- Wheelchair mode\n\n**Fitbit**\n- Sleep tracking\n- SpO2 monitoring\n- Affordable options\n\n### Specialized Devices\n- Embrace2 (seizure detection)\n- Livio AI (hearing aids)\n\n### Considerations\n- Battery life\n- Accessibility features\n- Accuracy\n\n*What do you wear?*"],
            ['category' => 'Technology', 'title' => 'AI Assistants: How Are You Using Them?', 'body' => "## AI Assistant Thread\n\n### Use Cases\n\n**ChatGPT / Claude**\n- Writing assistance\n- Explaining concepts\n- Brainstorming\n- Coding help\n\n**Seeing AI / Envision**\n- Reading text\n- Describing scenes\n- Identifying people\n\n### Accessibility Impact\n- Information access\n- Independence boost\n- Learning support\n\n*How do you use AI?*"],
            ['category' => 'Technology', 'title' => 'Social Media Accessibility Tips', 'body' => "## Making Social Media Better\n\n### For Everyone\n- Add alt text to images\n- Caption your videos\n- Use CamelCase hashtags\n- Avoid flashing content\n\n### Platform Tips\n\n**Twitter/X**\n- Enable alt text reminder\n\n**Instagram**\n- Auto-captions available\n\n**TikTok**\n- Add captions!\n\n*Be part of the solution!*"],
            ['category' => 'Technology', 'title' => 'Augmentative Communication (AAC) Apps', 'body' => "## AAC App Comparison\n\n### iPad Apps\n\n**Proloquo2Go** - \$250\n- Comprehensive\n- Highly customizable\n\n**TouchChat** - \$150\n- Good for motor impairments\n\n**TD Snap** - Free with device\n- Intuitive layout\n\n### Free Options\n- CBoard\n- Open AAC\n- Google TalkBack symbols\n\n*AAC users, what works for you?*"],
            ['category' => 'Technology', 'title' => 'Website Accessibility: What Drives You Crazy?', 'body' => "## Accessibility Rants Welcome\n\n### Common Frustrations\n- No alt text\n- Keyboard traps\n- Poor contrast\n- Auto-playing video\n- CAPTCHAs\n- Timeout too fast\n\n### Success Stories\n+ Well-designed gov sites\n+ Apple's documentation\n+ AbleLink \uD83D\uDE09\n\n### Taking Action\n- Report issues\n- Use accessibility overlays cautiously\n- Advocate for change\n\n*What's your biggest web pet peeve?*"],
            ['category' => 'Technology', 'title' => 'Budget Tech for Accessibility', 'body' => "## Affordable Accessibility\n\n### Free Software\n- NVDA screen reader\n- Windows Magnifier\n- Voice Access\n- Google Live Caption\n\n### Budget Hardware\n- USB foot pedals (\$30)\n- Ergonomic mice (\$25)\n- Ring light for low vision (\$20)\n\n### Funding\n- VR services\n- Nonprofits (Lions Club, etc.)\n- Tech refurb programs\n\n*Share your budget finds!*"],
            ['category' => 'Technology', 'title' => 'Future Tech: What Excites You?', 'body' => "## Tech on the Horizon\n\n### Exciting Developments\n\n**Brain-Computer Interfaces**\n- Neuralink and competitors\n- Potential for paralysis\n\n**Advanced Prosthetics**\n- Mind-controlled limbs\n- Sensory feedback\n\n**AR Glasses**\n- Real-time captioning\n- Navigation overlays\n\n**Self-Driving Cars**\n- Independence for non-drivers\n\n*What future tech are you most excited about?*"],
        ];

        // Get all users for random assignment
        $users = User::all();
        $userCount = $users->count();

        foreach ($forumPosts as $index => $post) {
            $randomUser = $users[$index % $userCount];
            
            ForumThread::create([
                'user_id' => $randomUser->id,
                'title' => $post['title'],
                'body' => $post['body'],
                'category' => $post['category'],
                'status' => 'active',
            ]);
        }
        
        $this->command->info('   Created ' . count($forumPosts) . ' forum posts across ' . count($categories) . ' categories');
    }

    private function getWelcomePostBody(): string
    {
        return "## Welcome to Our Community! 🌟

We're so glad you're here! This forum is a safe space for people with disabilities, caregivers, and allies to connect, share, and support each other.

### What You Can Do Here

- **Ask Questions**: No question is too simple
- **Share Experiences**: Your story matters
- **Offer Support**: Help others on their journey
- **Find Resources**: Discover helpful tools and services

### Forum Categories

| Category | Description |
|----------|-------------|
| General | Casual conversations and announcements |
| Accessibility | Tech, tools, and accessibility tips |
| Employment | Job seeking, workplace issues |
| Health | Wellness, healthcare, self-care |
| Technology | Assistive tech and innovations |

### Community Guidelines

1. **Be Respectful**: Treat everyone with dignity
2. **Stay Supportive**: We're all in this together
3. **Protect Privacy**: Don't share others' information
4. **Report Issues**: Flag inappropriate content

*Welcome aboard! Introduce yourself below!* 👋";
    }

    private function getGettingStartedBody(): string
    {
        return "## Getting Started on AbleLink

### Step 1: Complete Your Profile

A complete profile helps us personalize your experience:
- Add your skills and interests
- Set accessibility preferences
- Upload a profile photo (optional)

### Step 2: Explore Features

**Job Board**
Find disability-friendly employers and accessible positions.

**Learning Hub**
Discover courses designed with accessibility in mind.

**Community Events**
Join virtual and in-person gatherings.

**AI Assistant**
Get personalized recommendations and help.

### Step 3: Connect

- Join forum discussions
- Attend community events
- Connect with caregivers or volunteers

### Need Help?

Use our AI chatbot or post in the forum - we're here for you!

*Happy exploring!* 🚀";
    }
}
