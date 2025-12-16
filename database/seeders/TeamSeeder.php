<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Team Members with premium event organizer data
        $teamMembers = [
            [
                'name' => 'Rizki Ahmad Wijaya',
                'position' => 'Chief Executive Officer',
                'department' => 'Leadership',
                'bio' => 'Seasoned event professional with 15+ years of experience in creating world-class corporate events and concerts across Indonesia.',
                'skills' => ['Strategic Planning', 'Client Relations', 'Business Development', 'Event Architecture']
            ],
            [
                'name' => 'Sarah Permata',
                'position' => 'Creative Director',
                'department' => 'Creative',
                'bio' => 'Award-winning creative mind specializing in immersive event experiences and innovative concept development for luxury brands.',
                'skills' => ['Creative Direction', 'Concept Design', 'Brand Experience', 'Visual Storytelling']
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 'Operations Director',
                'department' => 'Operations',
                'bio' => 'Operations expert with meticulous attention to detail, ensuring flawless execution of events from conception to completion.',
                'skills' => ['Operations Management', 'Logistics', 'Quality Control', 'Team Leadership']
            ],
            [
                'name' => 'Maya Putri',
                'position' => 'Senior Event Coordinator',
                'department' => 'Operations',
                'bio' => 'Passionate event coordinator with expertise in managing complex multi-day conferences and high-profile corporate gatherings.',
                'skills' => ['Event Planning', 'Vendor Management', 'Timeline Coordination', 'Problem Solving']
            ],
            [
                'name' => 'Andri Kurniawan',
                'position' => 'Technical Director',
                'department' => 'Production',
                'bio' => 'Technical production specialist with extensive knowledge of audio-visual systems, staging, and cutting-edge event technology.',
                'skills' => ['Technical Production', 'Sound Engineering', 'Lighting Design', 'Live Streaming']
            ],
            [
                'name' => 'Lisa Handayani',
                'position' => 'Marketing & Communications Manager',
                'department' => 'Marketing',
                'bio' => 'Strategic marketing professional focused on brand promotion and digital marketing for high-end events and entertainment.',
                'skills' => ['Digital Marketing', 'Brand Strategy', 'Social Media', 'Public Relations']
            ],
            [
                'name' => 'David Pratama',
                'position' => 'Finance Director',
                'department' => 'Leadership',
                'bio' => 'Financial expert ensuring budget optimization and profitability while maintaining premium quality standards for all events.',
                'skills' => ['Financial Planning', 'Budget Management', 'Cost Optimization', 'Risk Assessment']
            ],
            [
                'name' => 'Amanda Susilo',
                'position' => 'Talent & Artist Relations',
                'department' => 'Creative',
                'bio' => 'Specialist in artist management and talent acquisition, building relationships with top performers and entertainers.',
                'skills' => ['Artist Management', 'Talent Booking', 'Contract Negotiation', 'Artist Relations']
            ],
            [
                'name' => 'Fajar Hermawan',
                'position' => 'Safety & Compliance Manager',
                'department' => 'Operations',
                'bio' => 'Dedicated professional ensuring all events meet safety standards and regulatory requirements for optimal guest experience.',
                'skills' => ['Safety Management', 'Risk Assessment', 'Compliance', 'Emergency Planning']
            ]
        ];

        foreach ($teamMembers as $index => $member) {
            Team::create([
                'name' => $member['name'],
                'position' => $member['position'],
                'bio' => $member['bio'],
                'email' => Str::slug($member['name'], '.') . '@dazzlingpro.id',
                'phone' => '+62 812 3456 78' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'photo_url' => null,
                'linkedin_url' => 'https://linkedin.com/in/' . Str::slug($member['name']),
                'instagram_url' => 'https://instagram.com/' . Str::slug($member['name']),
                'facebook_url' => null,
                'twitter_url' => null,
                'skills' => json_encode($member['skills']),
                'department' => $member['department'] === 'Leadership' ? 'management' :
                             ($member['department'] === 'Creative' ? 'creative' :
                             ($member['department'] === 'Operations' ? 'operation' :
                             ($member['department'] === 'Production' ? 'technical' : 'marketing'))),
                'is_active' => true,
                'is_featured' => $index < 6, // First 6 members are featured
                'sort_order' => $index
            ]);
        }

        $this->command->info('Team members seeded successfully!');
    }
}