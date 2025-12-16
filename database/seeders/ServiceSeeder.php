<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => 'Event Planning',
                'slug' => 'event-planning',
                'description' => 'Perencanaan acara dari konsep hingga eksekusi',
                'icon_url' => '📋',
                'full_description' => 'Layanan perencanaan acara profesional yang membantu Anda mewujudkan acara impian. Tim kami berpengalaman dalam mengorganisir berbagai jenis acara, dari pertemuan kecil hingga konser besar. Kami menangani semua detail penting termasuk pemilihan venue, koordinasi vendor, manajemen waktu, dan eksekusi yang sempurna.',
                'features' => [
                    'Konsultasi konsep acara',
                    'Manajemen anggaran',
                    'Koordinasi vendor',
                    'Timeline dan jadwal detail',
                    'Manajemen risiko',
                    'Supervisi hari-H'
                ],
                'packages' => [
                    [
                        'name' => 'Basic',
                        'price' => 'Rp 5.000.000',
                        'duration' => 'Acara 4 jam',
                        'features' => [
                            'Konsultasi 2x pertemuan',
                            'Koordinasi 5 vendor',
                            'Timeline acara',
                            'Supervisi 1 koordinator'
                        ]
                    ],
                    [
                        'name' => 'Premium',
                        'price' => 'Rp 10.000.000',
                        'duration' => 'Acara 8 jam',
                        'features' => [
                            'Konsultasi unlimited',
                            'Koordinasi 10 vendor',
                            'Timeline detail',
                            'Supervisi 2 koordinator',
                            'Dekorasi dasar'
                        ]
                    ],
                    [
                        'name' => 'VIP',
                        'price' => 'Rp 20.000.000',
                        'duration' => 'Full day',
                        'features' => [
                            'Konsultasi & brainstorming',
                            'Koordinasi unlimited vendor',
                            'Manajemen penuh',
                            'Tim supervisi 3 orang',
                            'Dekorasi premium',
                            'Dokumentasi foto & video'
                        ]
                    ]
                ],
                'image_url' => '/images/services/event-planning.jpg',
                'is_active' => true,
                'sort_order' => 0
            ],
            [
                'title' => 'Wedding Organizer',
                'slug' => 'wedding-organizer',
                'description' => 'Layanan lengkap untuk pernikahan impian Anda',
                'icon_url' => '💒',
                'full_description' => 'Wujudkan pernikahan impian Anda dengan layanan wedding organizer terbaik. Kami memahami bahwa pernikahan adalah momen paling berharga dalam hidup, dan kami berkomitmen untuk membuatnya sempurna. Dari pre-wedding hingga hari-H, tim profesional kami akan memastikan setiap detail berjalan lancar.',
                'features' => [
                    'Konsep tema pernikahan',
                    'Vendor terpercaya (MUA, gaun, dekor)',
                    'Manajemen undangan',
                    'Koordinasi acara adat',
                    'Photo & video documentation',
                    'Gift dan souvenir management'
                ],
                'packages' => [
                    [
                        'name' => 'Silver Package',
                        'price' => 'Rp 15.000.000',
                        'duration' => '1 hari acara',
                        'features' => [
                            'Konsep dasar pernikahan',
                            'Dekorasi standar',
                            'MUA pengantin',
                            'Dokumentasi foto 200 foto',
                            'Koordinator 1 orang'
                        ]
                    ],
                    [
                        'name' => 'Gold Package',
                        'price' => 'Rp 30.000.000',
                        'duration' => '2 hari acara',
                        'features' => [
                            'Konsep premium pernikahan',
                            'Dekorasi mewah',
                            'MUA pengantin & keluarga',
                            'Dokumentasi foto + video',
                            'Koordinator 2 orang',
                            'Entertainment akustik'
                        ]
                    ],
                    [
                        'name' => 'Platinum Package',
                        'price' => 'Rp 50.000.000',
                        'duration' => '3 hari acara',
                        'features' => [
                            'Konsep VIP pernikahan',
                            'Dekorasi ultra mewah',
                            'MUA semua keluarga',
                            'Dokumentasi cinematic',
                            'Koordinator 3 orang',
                            'Live band entertainment',
                            'Honeymoon preparation'
                        ]
                    ]
                ],
                'image_url' => '/images/services/wedding-organizer.jpg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Corporate Events',
                'slug' => 'corporate-events',
                'description' => 'Acara perusahaan yang profesional dan berkesan',
                'icon_url' => '🏢',
                'full_description' => 'Spesialis dalam mengorganisir acara korporat yang meningkatkan brand image dan engagement perusahaan Anda. Kami berpengalaman dalam berbagai jenis acara bisnis, dari meeting internal hingga konferensi internasional. Setiap detail dirancang secara profesional untuk mencapai tujuan bisnis Anda.',
                'features' => [
                    'Brand activation',
                    'Product launching',
                    'Corporate gathering',
                    'Annual meeting & conference',
                    'Team building activities',
                    'Award night ceremony'
                ],
                'packages' => [
                    [
                        'name' => 'Business Meeting',
                        'price' => 'Rp 7.500.000',
                        'duration' => 'Half day',
                        'features' => [
                            'Meeting room setup',
                            'Coffee break',
                            'LCD & sound system',
                            'Notulensi rapat',
                            'Koordinator 1 orang'
                        ]
                    ],
                    [
                        'name' => 'Conference Package',
                        'price' => 'Rp 25.000.000',
                        'duration' => 'Full day',
                        'features' => [
                            'Venue premium',
                            'Full catering',
                            'Registration system',
                            'Sound & lighting',
                            'MC profesional',
                            'Dokumentasi'
                        ]
                    ],
                    [
                        'name' => 'Corporate Gala',
                        'price' => 'Rp 75.000.000',
                        'duration' => 'Full event',
                        'features' => [
                            'Venue bintang 5',
                            'Full production',
                            'Entertainment package',
                            'Red carpet setup',
                            'Award trophies',
                            'Live streaming',
                            'Tim profesional 5 orang'
                        ]
                    ]
                ],
                'image_url' => '/images/services/corporate-events.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Hiburan berkualitas untuk semua jenis acara',
                'icon_url' => '🎤',
                'full_description' => 'Penyedia layanan hiburan profesional untuk berbagai jenis acara. Kami memiliki jaringan artis, band, dan entertainer terbaik di Indonesia. Dari musik live hingga pertunjukan magis, kami menyediakan hiburan yang disesuaikan dengan tema dan kebutuhan acara Anda.',
                'features' => [
                    'Live band & music performance',
                    'DJ & electronic music',
                    'Traditional dance performance',
                    'Magic show & illusionist',
                    'Celebrity & influencer booking',
                    'Cultural performances'
                ],
                'packages' => [
                    [
                        'name' => 'Acoustic Performance',
                        'price' => 'Rp 3.500.000',
                        'duration' => '2 jam',
                        'features' => [
                            'Acoustic duo/trio',
                            'Sound system standar',
                            'Song request (10 lagu)',
                            'Master of ceremony'
                        ]
                    ],
                    [
                        'name' => 'Full Band Package',
                        'price' => 'Rp 8.000.000',
                        'duration' => '3 jam',
                        'features' => [
                            'Full band (5-6 person)',
                            'Professional sound system',
                            'Lighting dasar',
                            'Song list custom',
                            'MC + audience interaction'
                        ]
                    ],
                    [
                        'name' => 'Entertainment Showcase',
                        'price' => 'Rp 20.000.000',
                        'duration' => 'Full performance',
                        'features' => [
                            'Celebrity guest star',
                            'Full production',
                            'Professional stage',
                            'Lighting & special effects',
                            'Dance performance',
                            'Tim teknis 3 orang'
                        ]
                    ]
                ],
                'image_url' => '/images/services/entertainment.jpg',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'title' => 'Decoration',
                'slug' => 'decoration',
                'description' => 'Dekorasi kreatif sesuai tema acara',
                'icon_url' => '🎨',
                'full_description' => 'Layanan dekorasi kreatif yang mengubah ruangan biasa menjadi venue yang luar biasa. Tim desainer kami berpengalaman dalam menciptakan berbagai tema dekorasi, dari yang elegan dan klasik hingga modern dan minimalis. Kami menggunakan bahan berkualitas tinggi untuk memastikan hasil terbaik.',
                'features' => [
                    'Theme decoration design',
                    'Floral arrangement',
                    'Lighting design',
                    'Backdrop & stage setup',
                    'Table centerpiece',
                    'Entrance gate decoration'
                ],
                'packages' => [
                    [
                        'name' => 'Basic Decor',
                        'price' => 'Rp 4.000.000',
                        'duration' => 'Setup 1x',
                        'features' => [
                            'Theme standar',
                            'Backdrop utama',
                            'Floral simple',
                            'Lighting dasar',
                            'Gate sederhana'
                        ]
                    ],
                    [
                        'name' => 'Premium Decor',
                        'price' => 'Rp 10.000.000',
                        'duration' => 'Setup 1x',
                        'features' => [
                            'Custom theme design',
                            'Backdrop premium',
                            'Fresh flower decoration',
                            'Professional lighting',
                            'Table centerpiece',
                            'Carpet & walkway'
                        ]
                    ],
                    [
                        'name' => 'Luxury Decor',
                        'price' => 'Rp 25.000.000',
                        'duration' => 'Setup 1x',
                        'features' => [
                            'Exclusive concept design',
                            'Multiple backdrop set',
                            'Import flowers',
                            'LED lighting & effects',
                            'Grand entrance gate',
                            'Themed photo booth',
                            'Tim dekorasi 5 orang'
                        ]
                    ]
                ],
                'image_url' => '/images/services/decoration.jpg',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'title' => 'Catering',
                'slug' => 'catering',
                'description' => 'Layanan catering dengan menu berkualitas',
                'icon_url' => '🍽️',
                'full_description' => 'Layanan catering premium dengan menu berkualitas tinggi yang disiapkan oleh chef profesional. Kami menyajikan berbagai masakan dari lokal hingga internasional, dengan pelayanan terbaik untuk membuat acara Anda lebih berkesan. Semua bahan makanan segar dan higienis.',
                'features' => [
                    'Indonesian & International cuisine',
                    'Halal certified kitchen',
                    'Professional service staff',
                    'Food tasting session',
                    'Custom menu design',
                    'Dietary accommodation'
                ],
                'packages' => [
                    [
                        'name' => 'Silver Buffet',
                        'price' => 'Rp 65.000/pax',
                        'duration' => 'Service 4 jam',
                        'features' => [
                            '20 menu types',
                            'Appetizer & dessert',
                            'Mineral water',
                            'Basic service staff',
                            'Standard buffet setup'
                        ]
                    ],
                    [
                        'name' => 'Gold Buffet',
                        'price' => 'Rp 95.000/pax',
                        'duration' => 'Service 4 jam',
                        'features' => [
                            '30 menu types',
                            'Live cooking station',
                            'Soup & salad bar',
                            'Premium dessert',
                            'Professional service',
                            'Elegant buffet display'
                        ]
                    ],
                    [
                        'name' => 'Platinum Service',
                        'price' => 'Rp 150.000/pax',
                        'duration' => 'Service 4 jam',
                        'features' => [
                            '50+ menu selection',
                            'International chef station',
                            'Wine & beverage pairing',
                            'Fine dining service',
                            'Themed food display',
                            'Captain & waiter service'
                        ]
                    ]
                ],
                'image_url' => '/images/services/catering.jpg',
                'is_active' => true,
                'sort_order' => 5
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('Services seeded successfully!');
    }
}