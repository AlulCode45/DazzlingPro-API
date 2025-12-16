<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Portfolio;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolios = [
            [
                'title' => 'Elegant Wedding Celebration',
                'slug' => 'elegant-wedding-celebration',
                'description' => 'A breathtaking wedding ceremony with elegant garden theme decoration, featuring premium floral arrangements and sophisticated lighting design that created a magical atmosphere for the newlyweds.',
                'short_description' => 'Luxurious garden-themed wedding with premium decorations',
                'client_name' => 'Mr. & Mrs. Anderson',
                'event_date' => '2024-01-15',
                'event_location' => 'Bali',
                'portfolio_category_id' => 1, // Wedding
                'images' => [
                    'https://picsum.photos/800/600?random=101',
                    'https://picsum.photos/800/600?random=102',
                    'https://picsum.photos/800/600?random=103',
                ],
                'featured_image' => 'https://picsum.photos/800/600?random=101',
                'featured' => true,
                'completed' => true,
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Corporate Gala Night 2024',
                'slug' => 'corporate-gala-night-2024',
                'description' => 'An exclusive corporate gala dinner for 500 distinguished guests, featuring world-class entertainment, gourmet dining experience, and prestigious award presentations.',
                'short_description' => 'Exclusive corporate gala dinner for 500 VIP guests',
                'client_name' => 'TechCorp Indonesia',
                'event_date' => '2024-02-20',
                'event_location' => 'Jakarta Convention Center',
                'portfolio_category_id' => 2, // Corporate
                'images' => [
                    'https://picsum.photos/800/600?random=201',
                    'https://picsum.photos/800/600?random=202',
                ],
                'featured_image' => 'https://picsum.photos/800/600?random=201',
                'featured' => true,
                'completed' => true,
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Product Launch Innovation Summit',
                'slug' => 'product-launch-innovation-summit',
                'description' => 'High-tech product launch event showcasing cutting-edge innovations, featuring interactive demonstrations, keynote speeches, and networking sessions for industry leaders.',
                'short_description' => 'Tech product launch with interactive demonstrations',
                'client_name' => 'Innovation Labs',
                'event_date' => '2024-03-10',
                'event_location' => 'Surabaya Tech Hub',
                'portfolio_category_id' => 2, // Corporate
                'images' => [
                    'https://picsum.photos/800/600?random=301',
                ],
                'featured_image' => 'https://picsum.photos/800/600?random=301',
                'featured' => false,
                'completed' => true,
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Cultural Heritage Festival',
                'slug' => 'cultural-heritage-festival',
                'description' => 'A vibrant cultural festival celebrating Indonesian heritage with traditional dance performances, authentic cuisine, handicraft exhibitions, and educational workshops.',
                'short_description' => 'Celebration of Indonesian cultural heritage',
                'client_name' => 'Cultural Foundation',
                'event_date' => '2024-04-05',
                'event_location' => 'Yogyakarta Cultural Park',
                'portfolio_category_id' => 3, // Festival
                'images' => [
                    'https://picsum.photos/800/600?random=401',
                    'https://picsum.photos/800/600?random=402',
                    'https://picsum.photos/800/600?random=403',
                    'https://picsum.photos/800/600?random=404',
                ],
                'featured_image' => 'https://picsum.photos/800/600?random=401',
                'featured' => false,
                'completed' => true,
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Jazz Under the Stars',
                'slug' => 'jazz-under-the-stars',
                'description' => 'An intimate outdoor jazz concert featuring renowned international and local artists, creating an unforgettable musical experience under the night sky.',
                'short_description' => 'Intimate outdoor jazz concert with renowned artists',
                'client_name' => 'Music Entertainment Group',
                'event_date' => '2024-05-18',
                'event_location' => 'Jakarta Amphitheater',
                'portfolio_category_id' => 4, // Concert
                'images' => [
                    'https://picsum.photos/800/600?random=501',
                    'https://picsum.photos/800/600?random=502',
                ],
                'featured_image' => 'https://picsum.photos/800/600?random=501',
                'featured' => true,
                'completed' => true,
                'status' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }
    }
}
