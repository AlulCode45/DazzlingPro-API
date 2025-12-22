<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Models\GalleryCategory;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category IDs
        $weddingCat = GalleryCategory::where('slug', 'wedding')->first();
        $corporateCat = GalleryCategory::where('slug', 'corporate')->first();
        $festivalCat = GalleryCategory::where('slug', 'festival')->first();
        $ceremonyCat = GalleryCategory::where('slug', 'ceremony')->first();

        $galleries = [
            [
                'title' => 'Wedding Ceremony',
                'category_id' => $weddingCat?->id ?? 1,
                'images' => json_encode(['https://images.unsplash.com/photo-1519741497674-611481863552?w=800']),
                'featured_image' => 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800',
                'is_featured' => true,
            ],
            [
                'title' => 'Corporate Gala',
                'category_id' => $corporateCat?->id ?? 2,
                'images' => json_encode(['https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800']),
                'featured_image' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
                'is_featured' => true,
            ],
            [
                'title' => 'Product Launch',
                'category_id' => $corporateCat?->id ?? 2,
                'images' => json_encode(['https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800']),
                'featured_image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800',
                'is_featured' => false,
            ],
            [
                'title' => 'Cultural Festival',
                'category_id' => $festivalCat?->id ?? 3,
                'images' => json_encode(['https://images.unsplash.com/photo-1527529482837-4698179dc4ce?w=800']),
                'featured_image' => 'https://images.unsplash.com/photo-1527529482837-4698179dc4ce?w=800',
                'is_featured' => true,
            ],
            [
                'title' => 'Award Ceremony',
                'category_id' => $ceremonyCat?->id ?? 4,
                'images' => json_encode(['https://images.unsplash.com/photo-1505373877840-8a6941b3e3a0?w=800']),
                'featured_image' => 'https://images.unsplash.com/photo-1505373877840-8a6941b3e3a0?w=800',
                'is_featured' => false,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
    }
}
