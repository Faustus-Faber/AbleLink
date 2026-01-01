<?php
//F11 - Akida Lisi

namespace Database\Seeders;

use App\Models\Education\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Run via: php artisan db:seed --class=CourseSeeder
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Introduction to Screen Readers',
                'summary' => 'Master the basics of JAWS and NVDA for navigating the web.',
                'description' => 'This course covers the fundamentals of using screen readers like JAWS and NVDA. You will learn commands for browsing, reading documents, and interacting with complex web applications.',
                'level' => 'beginner',
                'estimated_minutes' => 45,
            ],
            [
                'title' => 'Adaptive Cooking Basics: Safe Chopping',
                'summary' => 'Learn safe techniques for chopping and slicing with limited mobility or vision.',
                'description' => 'Cooking can be accessible for everyone. This module focuses on safe knife skills, using adaptive cutting boards, and techniques for chefs with one-handed use or visual impairments.',
                'level' => 'beginner',
                'estimated_minutes' => 30,
            ],
            [
                'title' => 'AI Tools for Visual Impairment',
                'summary' => 'Explore how AI apps like Be My Eyes and Envision can assist in daily life.',
                'description' => 'Artificial Intelligence is a game changer. We explore apps that describe surroundings, read handwriting, and identify objects, helping you gain more independence.',
                'level' => 'intermediate',
                'estimated_minutes' => 60,
            ],
            [
                'title' => 'Sign Language 101: Basic Greetings',
                'summary' => 'Start conversing in ASL with essential greetings and introductions.',
                'description' => 'A starter course for American Sign Language. Learn the alphabet, numbers 1-10, and common greetings to start connecting with the Deaf community.',
                'level' => 'beginner',
                'estimated_minutes' => 90,
            ],
            [
                'title' => 'Financial Independence for People with Disabilities',
                'summary' => 'Budgeting, benefits management, and financial planning tips.',
                'description' => 'Navigating finances can be complex. This course covers managing disability benefits, budgeting for adaptive equipment, and long-term financial planning strategies.',
                'level' => 'advanced',
                'estimated_minutes' => 120,
            ],
            [
                'title' => 'Yoga for Wheelchair Users',
                'summary' => 'Adapted yoga sequences to improve flexibility and reduce stress.',
                'description' => 'Experience the benefits of yoga from a seated position. These routines focus on upper body strength, breathing techniques, and flexibility, designed specifically for wheelchair users.',
                'level' => 'beginner',
                'estimated_minutes' => 40,
            ],
            [
                'title' => 'Setting up Smart Home for Accessibility',
                'summary' => 'Automate your home with voice assistants and smart devices.',
                'description' => 'Turn your home into an accessible hub. Learn to configure Alexa, Google Home, and smart lights to control your environment through voice or app-based interfaces.',
                'level' => 'intermediate',
                'estimated_minutes' => 75,
            ],
            [
                'title' => 'Coding with Voice Control',
                'summary' => 'Write code hands-free using Talon Voice and other tools.',
                'description' => 'For developers with RSI or mobility limitations, voice coding is powerful. Learn how to set up Talon Voice to write Python and JavaScript purely by speaking.',
                'level' => 'advanced',
                'estimated_minutes' => 150,
            ],
            [
                'title' => 'Braille Basics and Literacy',
                'summary' => 'An introduction to the Braille alphabet and reading techniques.',
                'description' => 'Understand the logic behind the Braille cell, memorize the alphabet, and practice basic reading skills. Essential for those transitioning to touch-based reading.',
                'level' => 'beginner',
                'estimated_minutes' => 120,
            ],
            [
                'title' => 'Resume Building for Inclusive Employers',
                'summary' => 'Craft a resume that highlights your strengths and addresses gaps.',
                'description' => 'Learn how to frame your unique experiences, address employment gaps effectively, and identify employers who value diversity and inclusion.',
                'level' => 'intermediate',
                'estimated_minutes' => 60,
            ],
            [
                'title' => 'Navigating Public Transport w/ Mobility Aids',
                'summary' => 'Tips and rights for using buses, trains, and subways.',
                'description' => 'Gain confidence in city travel. We cover legal rights, how to request assistance, getting on/off ramps safely, and planning accessible routes.',
                'level' => 'beginner',
                'estimated_minutes' => 45,
            ],
            [
                'title' => 'Introduction to Lip Reading',
                'summary' => 'Enhance communication by learning to read visual speech cues.',
                'description' => 'Supplement your hearing with lip reading. Learn to identify common mouth shapes and context clues to improve comprehension in noisy environments.',
                'level' => 'beginner',
                'estimated_minutes' => 90,
            ],
            [
                'title' => 'Managing Sensory Overload',
                'summary' => 'Strategies for neurodivergent individuals to cope with overstimulation.',
                'description' => 'Practical techniques for recognizing triggers, self-regulation, and creating sensory-friendly environments for work and home.',
                'level' => 'intermediate',
                'estimated_minutes' => 50,
            ],
            [
                'title' => 'Adaptive Gardening: Tools and Techniques',
                'summary' => 'Enjoy gardening with raised beds and ergonomic tools.',
                'description' => 'Gardening is therapeutic. Learn about vertical gardens, raised beds, and easy-grip tools that make gardening accessible for everyone.',
                'level' => 'beginner',
                'estimated_minutes' => 40,
            ],
            [
                'title' => 'Digital Accessibility Testing',
                'summary' => 'Learn to test websites for WCAG compliance.',
                'description' => 'A career-focused course. Learn how to use automated tools like WAVE and manual keyboard testing to audit websites for accessibility compliance.',
                'level' => 'advanced',
                'estimated_minutes' => 180,
            ],
            [
                'title' => 'Text-to-Speech Tools for Dyslexia',
                'summary' => 'Maximize reading comprehension with TTS software.',
                'description' => 'Review the best text-to-speech tools available on mobile and desktop. Learn to adjust speed and voices to suit your processing needs.',
                'level' => 'beginner',
                'estimated_minutes' => 30,
            ],
            [
                'title' => 'Mindfulness and Pain Management',
                'summary' => 'Meditation techniques to help manage chronic pain.',
                'description' => ' mindfulness-based stress reduction (MBSR) techniques specifically tailored to alter your relationship with chronic pain and discomfort.',
                'level' => 'intermediate',
                'estimated_minutes' => 45,
            ],
            [
                'title' => 'One-Handed Typing Techniques',
                'summary' => 'Increase typing speed using one-handed keyboard layouts.',
                'description' => 'Explore the Dvorak one-handed layouts and half-QWERTY techniques to type efficiently with a single hand.',
                'level' => 'intermediate',
                'estimated_minutes' => 60,
            ],
            [
                'title' => 'Guide Dog Etiquette and Care',
                'summary' => 'Best practices for working with and caring for a service animal.',
                'description' => 'For handlers and the public. Learn the do\'s and don\'ts of interacting with guide dogs, plus tips on grooming, feeding, and maintaining their training.',
                'level' => 'beginner',
                'estimated_minutes' => 50,
            ],
            [
                'title' => 'Advocacy 101: Knowing Your Rights',
                'summary' => 'Understand the disability laws in your region.',
                'description' => 'Empower yourself with knowledge. An overview of the ADA (Americans with Disabilities Act) and other global laws protecting your rights in employment, housing, and public spaces.',
                'level' => 'intermediate',
                'estimated_minutes' => 90,
            ],
        ];

        foreach ($courses as $data) {
            $slug = Str::slug($data['title']);
            
            // Avoid duplicate seeding collision
            if (Course::where('slug', $slug)->exists()) {
                continue;
            }

            Course::create([
                'title' => $data['title'],
                'slug' => $slug,
                'summary' => $data['summary'],
                'description' => $data['description'],
                'level' => $data['level'],
                'estimated_minutes' => $data['estimated_minutes'],
                'published_at' => now(), // Publish immediately
            ]);
        }

        $this->command->info('Seeded ' . count($courses) . ' courses into the Library.');
    }
}
