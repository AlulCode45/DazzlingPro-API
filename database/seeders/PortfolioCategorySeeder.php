<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PortfolioCategory;

class PortfolioCategorySeeder extends Seeder
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
                'description' => 'Wedding events and ceremonies',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Corporate',
                'slug' => 'corporate',
                'description' => 'Corporate events and business functions',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Festival',
                'slug' => 'festival',
                'description' => 'Cultural and music festivals',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Concert',
                'slug' => 'concert',
                'description' => 'Music concerts and performances',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Private',
                'slug' => 'private',
                'description' => 'Private parties and celebrations',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Government',
                'slug' => 'government',
                'description' => 'Government and official events',
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            PortfolioCategory::create($category);
        }
    }
}
