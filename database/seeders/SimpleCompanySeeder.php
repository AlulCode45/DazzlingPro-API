<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInformation;

class SimpleCompanySeeder extends Seeder
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

        $this->command->info('Company information seeded successfully!');
    }
}