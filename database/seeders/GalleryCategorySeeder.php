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
            [
                'name' => 'Wedding',
                'slug' => 'wedding',
            ],
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
                'name' => 'Concert',
                'slug' => 'concert',
            ],
            [
                'name' => 'Private',
                'slug' => 'private',
            ],
        ];

        foreach ($categories as $category) {
            GalleryCategory::create($category);
        }
    }
}
