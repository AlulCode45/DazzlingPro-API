<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventRental;

class EventRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = [
            [
                'name' => 'Sound System Pro',
                'category' => 'Sound',
                'specifications' => '20.000 Watt, Line Array',
                'price_per_day' => 15000000,
                'is_featured' => true,
            ],
            [
                'name' => 'LED Wall 4K',
                'category' => 'Video',
                'specifications' => '3x2 meter, P2.5',
                'price_per_day' => 25000000,
                'is_featured' => true,
            ],
            [
                'name' => 'Moving Head',
                'category' => 'Lighting',
                'specifications' => 'Beam 330W',
                'price_per_day' => 500000,
                'is_featured' => false,
            ],
            [
                'name' => 'Wireless Mic',
                'category' => 'Audio',
                'specifications' => 'Shure ULXD',
                'price_per_day' => 750000,
                'is_featured' => false,
            ],
            [
                'name' => 'Stage Platform',
                'category' => 'Stage',
                'specifications' => '1x2 meter, Aluminium',
                'price_per_day' => 150000,
                'is_featured' => false,
            ],
            [
                'name' => 'Tenda Dekorasi',
                'category' => 'Tent',
                'specifications' => '10x10 meter',
                'price_per_day' => 5000000,
                'is_featured' => false,
            ],
        ];

        foreach ($rentals as $rental) {
            EventRental::create($rental);
        }
    }
}
