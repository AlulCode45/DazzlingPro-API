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
                'name' => 'Festival Budaya',
                'slug' => 'festival-budaya',
                'description' => 'Festival dan perayaan budaya tradisional dan modern',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Acara Korporat',
                'slug' => 'acara-korporat',
                'description' => 'Konferensi, seminar, dan acara bisnis profesional',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pernikahan',
                'slug' => 'pernikahan',
                'description' => 'Pernikahan mewah dan acara pernikahan custom',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Konser & Musik',
                'slug' => 'konser-musik',
                'description' => 'Konser musik, festival musik, dan pertunjukan live',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Pameran',
                'slug' => 'pameran',
                'description' => 'Pameran produk, trade show, dan booth exhibition',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Acara Olahraga',
                'slug' => 'acara-olahraga',
                'description' => 'Turnamen, kompetisi, dan acara olahraga',
                'status' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Acara Sosial',
                'slug' => 'acara-sosial',
                'description' => 'Charity event, gathering, dan acara komunitas',
                'status' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Launching Produk',
                'slug' => 'launching-produk',
                'description' => 'Peluncuran produk dan brand activation',
                'status' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $category) {
            PortfolioCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Portfolio categories seeded successfully!');
    }
}
