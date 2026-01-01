<?php

// F20 - Akida Lisi

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
//F20
class AidProgramSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('aid_programs')) {
            $this->command?->warn('aid_programs table not found. Run migrations first.');
            return;
        }

        $programs = [
            [
                'title' => 'Disability Support Allowance',
                'agency' => 'Department of Social Services',
                'category' => 'Disability',
                'region' => 'National',
                'summary' => 'Monthly income support for eligible people with long-term disabilities.',
                'eligibility' => "Eligibility may include:\n- Certified disability or long-term health condition\n- Income/asset limits\n- Residency requirements",
                'benefits' => "Typical benefits:\n- Monthly cash assistance\n- Access to additional services and referrals",
                'how_to_apply' => "Apply online or in person.\nPrepare: ID, medical documentation, income proof.",
                'application_url' => 'https://example.gov/disability-support',
                'contact_phone' => '+1-800-000-0000',
                'contact_email' => 'support@example.gov',
                'tags' => ['disability', 'income', 'cash assistance'],
            ],
            [
                'title' => 'Accessible Housing Voucher Program',
                'agency' => 'Housing Authority',
                'category' => 'Housing',
                'region' => 'National',
                'summary' => 'Rent subsidy assistance prioritizing applicants needing accessible housing.',
                'eligibility' => "Eligibility may include:\n- Low to moderate income\n- Housing need\n- Verified disability (for accessibility priority)",
                'benefits' => "Typical benefits:\n- Rent subsidy\n- Accessibility modification support (case-by-case)",
                'how_to_apply' => "Join the waiting list online.\nProvide income verification and accommodation needs.",
                'application_url' => 'https://example.gov/housing-voucher',
                'contact_phone' => '+1-800-111-2222',
                'contact_email' => 'housing@example.gov',
                'tags' => ['housing', 'rent', 'accessibility'],
            ],
            [
                'title' => 'Assistive Technology Grant',
                'agency' => 'Ministry of Health',
                'category' => 'Assistive Technology',
                'region' => 'National',
                'summary' => 'Partial reimbursement for eligible assistive devices and accessibility tools.',
                'eligibility' => "Eligibility may include:\n- Prescription/assessment by a qualified professional\n- Device quote\n- Income-based co-pay",
                'benefits' => "Typical benefits:\n- Reimbursement for approved devices\n- Support for training/maintenance (limited)",
                'how_to_apply' => "Submit assessment + quote.\nProcessing times vary by region.",
                'application_url' => 'https://example.gov/assistive-tech',
                'contact_phone' => '+1-800-333-4444',
                'contact_email' => 'assistive-tech@example.gov',
                'tags' => ['assistive technology', 'devices', 'grant'],
            ],
        ];

        foreach ($programs as $p) {
            $slugBase = Str::slug($p['title']);
            $slug = $slugBase;
            $i = 2;
            while (DB::table('aid_programs')->where('slug', $slug)->exists()) {
                $slug = "{$slugBase}-{$i}";
                $i++;
            }

            DB::table('aid_programs')->insert([
                'slug' => $slug,
                'title' => $p['title'],
                'agency' => $p['agency'],
                'category' => $p['category'],
                'region' => $p['region'],
                'summary' => $p['summary'],
                'eligibility' => $p['eligibility'],
                'benefits' => $p['benefits'],
                'how_to_apply' => $p['how_to_apply'],
                'application_url' => $p['application_url'],
                'contact_phone' => $p['contact_phone'],
                'contact_email' => $p['contact_email'],
                'is_active' => true,
                'tags' => json_encode($p['tags']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command?->info('Aid programs seeded.');
    }
}
