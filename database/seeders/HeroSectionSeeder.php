<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSection;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Hero Sections for premium event organizer
        $heroSections = [
            [
                'title' => 'Create Unforgettable Events',
                'subtitle' => 'Premium Event Management Services',
                'description' => 'Transform your vision into extraordinary experiences with Indonesia\'s leading event organizer. From corporate conferences to spectacular concerts, we bring expertise to every celebration.',
                'primary_button_text' => 'Start Planning',
                'primary_button_url' => '#contact',
                'secondary_button_text' => 'View Portfolio',
                'secondary_button_url' => '#portfolio',
                'background_image_url' => '/images/hero-concert.jpg',
                'background_video_url' => null,
                'overlay_opacity' => 0.5,
                'text_color' => '#ffffff',
                'button_style' => 'default',
                'animation_type' => 'fade',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Corporate Events Excellence',
                'subtitle' => 'Professional Business Event Solutions',
                'description' => 'Elevate your brand with meticulously planned corporate events that leave lasting impressions. Our team ensures seamless execution from concept to completion.',
                'primary_button_text' => 'Get Quote',
                'primary_button_url' => '#contact',
                'secondary_button_text' => 'Our Services',
                'secondary_button_url' => '#services',
                'background_image_url' => null,
                'background_video_url' => '/videos/conference-intro.mp4',
                'overlay_opacity' => 0.6,
                'text_color' => '#ffffff',
                'button_style' => 'rounded',
                'animation_type' => 'slide',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Luxury Wedding Celebrations',
                'subtitle' => 'Your Dream Wedding, Perfected',
                'description' => 'Make your special day truly magical with our bespoke wedding planning services. From intimate gatherings to grand celebrations, we craft moments that last forever.',
                'primary_button_text' => 'Book Consultation',
                'primary_button_url' => '#consultation',
                'secondary_button_text' => 'Wedding Gallery',
                'secondary_button_url' => '#gallery',
                'background_image_url' => '/images/wedding-hero.jpg',
                'background_video_url' => null,
                'overlay_opacity' => 0.4,
                'text_color' => '#f8f8f8',
                'button_style' => 'outline',
                'animation_type' => 'zoom',
                'is_active' => false,
                'sort_order' => 2,
            ],
            [
                'title' => 'Concerts & Entertainment',
                'subtitle' => 'Spectacular Live Experiences',
                'description' => 'From international artist concerts to cultural festivals, we deliver world-class entertainment events that captivate audiences and create unforgettable memories.',
                'primary_button_text' => 'Upcoming Events',
                'primary_button_url' => '#events',
                'secondary_button_text' => 'Artist Booking',
                'secondary_button_url' => '#booking',
                'background_image_url' => null,
                'background_video_url' => '/videos/concert-highlights.mp4',
                'overlay_opacity' => 0.7,
                'text_color' => '#ffffff',
                'button_style' => 'ghost',
                'animation_type' => 'bounce',
                'is_active' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($heroSections as $index => $section) {
            HeroSection::create([
                'title' => $section['title'],
                'subtitle' => $section['subtitle'],
                'description' => $section['description'],
                'primary_button_text' => $section['primary_button_text'],
                'primary_button_url' => $section['primary_button_url'],
                'secondary_button_text' => $section['secondary_button_text'],
                'secondary_button_url' => $section['secondary_button_url'],
                'background_image_url' => $section['background_image_url'],
                'background_video_url' => $section['background_video_url'],
                'overlay_opacity' => $section['overlay_opacity'],
                'text_color' => $section['text_color'],
                'button_style' => $section['button_style'],
                'animation_type' => $section['animation_type'],
                'is_active' => $section['is_active'],
                'sort_order' => $section['sort_order'],
            ]);
        }

        $this->command->info('Hero sections seeded successfully!');
    }
}
