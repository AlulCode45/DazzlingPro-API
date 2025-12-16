<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'John Doe',
                'role' => 'CEO, Tech Corp',
                'content' => 'Dazzling Events provided exceptional service for our annual corporate gala. The attention to detail was remarkable!',
                'rating' => 5,
                'image_url' => null,
            ],
            [
                'name' => 'Sarah Johnson',
                'role' => 'Wedding Planner',
                'content' => 'I\'ve worked with many event companies, but Dazzling Events stands out. Their professionalism and creativity are unmatched.',
                'rating' => 5,
                'image_url' => null,
            ],
            [
                'name' => 'Michael Chen',
                'role' => 'Marketing Manager',
                'content' => 'Our product launch was a huge success thanks to Dazzling Events. They handled everything flawlessly.',
                'rating' => 4,
                'image_url' => null,
            ],
            [
                'name' => 'Emily Rodriguez',
                'role' => 'HR Director',
                'content' => 'The team building event organized by Dazzling was exactly what our company needed. Highly recommend!',
                'rating' => 5,
                'image_url' => null,
            ],
            [
                'name' => 'David Kim',
                'role' => 'Festival Organizer',
                'content' => 'Dazzling Events helped us organize a music festival for 5000 people. Their expertise was invaluable.',
                'rating' => 5,
                'image_url' => null,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
