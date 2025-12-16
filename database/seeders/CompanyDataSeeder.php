<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInformation;
use App\Models\HeroSection;
use App\Models\Team;
use App\Models\FAQ;
use Illuminate\Support\Str;

class CompanyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Company Information
        CompanyInformation::create([
            'company_name' => 'Dazzling Pro',
            'email' => 'info@dazzlingpro.id',
            'phone' => '+62 21 1234 5678',
            'address' => 'Jakarta, Indonesia',
            'social_media' => json_encode([
                ['platform' => 'instagram', 'url' => 'https://instagram.com/dazzlingpro'],
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/dazzlingpro'],
                ['platform' => 'youtube', 'url' => 'https://youtube.com/@dazzlingpro']
            ]),
            'operating_hours' => json_encode([
                ['day' => 'Monday - Friday', 'hours' => '09:00 - 18:00'],
                ['day' => 'Saturday', 'hours' => '10:00 - 15:00'],
                ['day' => 'Sunday', 'hours' => 'Closed']
            ]),
            'seo_meta' => json_encode([
                'title' => 'Dazzling Pro - Premium Creative Event Organizer Indonesia',
                'description' => 'Indonesia\'s premier creative event organizer since 2018. Crafting artistic and memorable experiences for festivals, concerts, corporate events, weddings, and more.',
                'keywords' => ['event organizer', 'creative events', 'concert organizer', 'wedding planner', 'corporate events']
            ]),
            'is_active' => true
        ]);

        // Hero Section
        HeroSection::create([
            'title' => 'Crafting Artistic & Memorable Experiences',
            'subtitle' => 'Since 2018',
            'description' => 'Turning Ideas Into Experiences — Where creativity meets precision, and every event becomes a masterpiece.',
            'primary_button_text' => 'Create Your Event With Us',
            'primary_button_url' => '#contact',
            'secondary_button_text' => 'View Our Work',
            'secondary_button_url' => '#portfolio',
            'background_image_url' => '/uploads/hero/hero-bg.jpg',
            'overlay_opacity' => 0.5,
            'text_color' => '#ffffff',
            'button_style' => 'default',
            'animation_type' => 'fade',
            'is_active' => true,
            'order' => 0
        ]);

        // Team Members
        $teamMembers = [
            ['name' => 'Ahmad Rizki', 'position' => 'Chief Executive Officer', 'department' => 'Leadership'],
            ['name' => 'Siti Nurhaliza', 'position' => 'Creative Director', 'department' => 'Creative'],
            ['name' => 'Budi Santoso', 'position' => 'Project Manager', 'department' => 'Operations'],
            ['name' => 'Maya Putri', 'position' => 'Event Coordinator', 'department' => 'Operations'],
            ['name' => 'Dedi Kurniawan', 'position' => 'Production Lead', 'department' => 'Production'],
            ['name' => 'Lisa Handayani', 'position' => 'Marketing Officer', 'department' => 'Marketing']
        ];

        $departmentMap = [
            'Leadership' => 'management',
            'Creative' => 'creative',
            'Operations' => 'operation',
            'Production' => 'technical',
            'Marketing' => 'marketing',
        ];

        foreach ($teamMembers as $index => $member) {
            $department = $departmentMap[$member['department']] ?? null;
            $linkedinHandle = Str::slug($member['name']);

            Team::create([
                'name' => $member['name'],
                'position' => $member['position'],
                'department' => $department,
                'bio' => 'Experienced professional with a passion for creating extraordinary events.',
                'email' => Str::slug($member['name'], '.') . '@dazzlingpro.id',
                'phone' => '+62 812 3456 78' . str_pad($index, 2, '0', STR_PAD_LEFT),
                'photo_url' => null,
                'linkedin_url' => 'https://linkedin.com/in/' . $linkedinHandle,
                'instagram_url' => 'https://instagram.com/' . $linkedinHandle,
                'skills' => json_encode(['Event Planning', 'Project Management', 'Creative Design']),
                'is_active' => true,
                'is_featured' => $index < 3,
                'sort_order' => $index
            ]);
        }

        // FAQs
        $faqs = [
            [
                'question' => 'What types of events do you organize?',
                'answer' => 'We organize a wide range of events including corporate events, concerts, festivals, weddings, product launches, and private parties. Our team specializes in creating unique and memorable experiences tailored to your specific needs.',
                'category' => 'Services'
            ],
            [
                'question' => 'How far in advance should we book your services?',
                'answer' => 'We recommend booking our services at least 3-6 months in advance for large-scale events and 1-2 months for smaller events. This allows us adequate time to plan and execute your event to perfection.',
                'category' => 'Booking'
            ],
            [
                'question' => 'Do you work with a specific budget range?',
                'answer' => 'We work with clients across various budget ranges. During our initial consultation, we\'ll discuss your budget requirements and create a customized proposal that maximizes value while delivering exceptional results.',
                'category' => 'Pricing'
            ],
            [
                'question' => 'Can you handle events outside of Jakarta?',
                'answer' => 'Yes, we organize events throughout Indonesia and internationally. Our team has experience managing events in various locations and can coordinate all logistics regardless of the venue.',
                'category' => 'Services'
            ],
            [
                'question' => 'What makes Dazzling Pro different from other event organizers?',
                'answer' => 'We combine creative vision with meticulous execution. Our team brings artistic innovation to every project while ensuring every detail is perfectly executed. We\'re known for our personalized approach and commitment to exceeding client expectations.',
                'category' => 'About Us'
            ]
        ];

        foreach ($faqs as $index => $faq) {
            FAQ::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'is_active' => true,
                'sort_order' => $index
            ]);
        }

        $this->command->info('Company data seeded successfully!');
    }
}