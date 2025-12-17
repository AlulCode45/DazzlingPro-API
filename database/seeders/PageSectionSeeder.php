<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'title' => 'Crafting Artistic & Memorable Experiences',
                'content' => [
                    'title' => 'Crafting Artistic & Memorable Experiences',
                    'subtitle' => 'Since 2018',
                    'description' => 'Turning Ideas Into Experiences — Where creativity meets precision, and every event becomes a masterpiece.',
                    'primary_button_text' => 'Create Your Event With Us',
                    'primary_button_url' => '#contact',
                    'secondary_button_text' => 'View Our Work',
                    'secondary_button_url' => '#portfolio',
                    'background_image' => '',
                    'overlay_opacity' => 0.5,
                    'text_color' => '#ffffff',
                    'button_style' => 'default',
                    'animation_type' => 'fade',
                ],
                'is_active' => true,
            ],
            [
                'key' => 'contact',
                'title' => 'Contact CTA',
                'content' => [
                    'badge' => 'Ready to Create Magic?',
                    'headline' => 'Let\'s Plan Your',
                    'subheadline' => 'Next Event',
                    'description' => 'Transform your vision into an unforgettable experience. Contact us today and let\'s create something extraordinary together.',
                    'cta_label' => 'Let\'s Plan Your Event',
                    'cta_link' => '#',
                    'background_image' => null,
                ],
                'is_active' => true,
            ],
            [
                'key' => 'philosophy',
                'title' => 'Our Philosophy',
                'content' => [
                    'label' => 'Our Philosophy',
                    'headline' => 'Event',
                    'subheadline_1' => 'is an',
                    'subheadline_2' => 'Art',
                    'description' => 'At Dazzling Pro, we believe every event is a canvas waiting to be transformed into a masterpiece. Our philosophy is rooted in the conviction that experiences should evoke emotion, inspire wonder, and leave lasting impressions.',
                    'pillars' => [
                        [
                            'title' => 'Creativity',
                            'description' => 'Pushing boundaries to deliver unique, unforgettable concepts.',
                        ],
                        [
                            'title' => 'Precision',
                            'description' => 'Meticulous attention to every detail, from vision to execution.',
                        ],
                        [
                            'title' => 'Imagination',
                            'description' => 'Transforming dreams into tangible, breathtaking realities.',
                        ],
                        [
                            'title' => 'Emotion',
                            'description' => 'Creating moments that resonate deep within the soul.',
                        ],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'key' => 'awards',
                'title' => 'Awards & Recognition',
                'content' => [
                    'label' => 'Recognition',
                    'headline' => 'The Most Trusted Industry Creative',
                    'award_title' => 'ASEAN Trusted Award 2024',
                    'award_date' => 'January 27, 2024',
                    'description' => 'Recognized for excellence in event management and creative innovation.',
                ],
                'is_active' => true,
            ],
            [
                'key' => 'legality',
                'title' => 'Company Legality',
                'content' => [
                    'label' => 'Legal & Compliance',
                    'headline' => 'Company',
                    'subheadline' => 'Legality',
                    'description' => 'PT Dazzling Pro Indonesia adalah perusahaan yang terdaftar dan beroperasi secara legal dengan seluruh dokumen perizinan yang lengkap.',
                    'documents' => [
                        [
                            'icon' => 'building',
                            'title' => 'Akta Pendirian Perusahaan',
                            'number' => 'No. 45 Tahun 2018',
                            'description' => 'Notaris Dr. Hendra Wijaya, S.H., M.Kn.',
                            'status' => 'Verified',
                        ],
                        [
                            'icon' => 'file-text',
                            'title' => 'NIB (Nomor Induk Berusaha)',
                            'number' => '1234567890123',
                            'description' => 'OSS - Online Single Submission',
                            'status' => 'Active',
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'SIUP (Surat Izin Usaha Perdagangan)',
                            'number' => '503/1234/PK/2018',
                            'description' => 'Dinas Perindustrian dan Perdagangan',
                            'status' => 'Valid',
                        ],
                        [
                            'icon' => 'file-text',
                            'title' => 'TDP (Tanda Daftar Perusahaan)',
                            'number' => '13.05.1.46.12345',
                            'description' => 'Kementerian Perdagangan RI',
                            'status' => 'Registered',
                        ],
                        [
                            'icon' => 'award',
                            'title' => 'NPWP Perusahaan',
                            'number' => '01.234.567.8-901.000',
                            'description' => 'Direktorat Jenderal Pajak',
                            'status' => 'Active',
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Sertifikat ISO 9001:2015',
                            'number' => 'QMS-2023-001234',
                            'description' => 'Quality Management System',
                            'status' => 'Certified',
                        ],
                    ],
                ],
                'is_active' => true,
            ],
            [
                'key' => 'timeline',
                'title' => 'Company Timeline',
                'content' => [
                    'label' => 'Our Story',
                    'headline' => 'Journey Through',
                    'subheadline' => 'Time',
                    'events' => [
                        [
                            'year' => '2018',
                            'title' => 'Foundation',
                            'description' => 'Dazzling Pro was born',
                        ],
                        [
                            'year' => '2019',
                            'title' => 'First Major Event',
                            'description' => 'Corporate gala success',
                        ],
                        [
                            'year' => '2020',
                            'title' => 'Digital Pivot',
                            'description' => 'Virtual event innovation',
                        ],
                        [
                            'year' => '2021',
                            'title' => 'Team Expansion',
                            'description' => 'Growing our creative family',
                        ],
                        [
                            'year' => '2022',
                            'title' => 'National Recognition',
                            'description' => '50+ events delivered',
                        ],
                        [
                            'year' => '2023',
                            'title' => 'International Stage',
                            'description' => 'Thengul Festival launch',
                        ],
                        [
                            'year' => '2024',
                            'title' => 'ASEAN Award',
                            'description' => 'Industry recognition',
                        ],
                        [
                            'year' => '2025',
                            'title' => 'The Future',
                            'description' => 'New horizons await',
                        ],
                    ],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($sections as $section) {
            PageSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
