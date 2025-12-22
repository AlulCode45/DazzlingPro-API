<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryCategory;

class GalleryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // EVENT CATEGORIES
            [
                'name' => 'Corporate',
                'slug' => 'corporate',
            ],
            [
                'name' => 'Festival',
                'slug' => 'festival',
            ],
            [
                'name' => 'Ceremony',
                'slug' => 'ceremony',
            ],
            [
                'name' => 'Workshop',
                'slug' => 'workshop',
            ],
            [
                'name' => 'Birthday Party',
                'slug' => 'birthday-party',
            ],
            // WEDDING CATEGORIES
            [
                'name' => 'Wedding',
                'slug' => 'wedding',
            ],
            [
                'name' => 'Prewedding',
                'slug' => 'prewedding',
            ],
            [
                'name' => 'Engagement',
                'slug' => 'engagement',
            ],
        ];

        foreach ($categories as $category) {
            GalleryCategory::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
