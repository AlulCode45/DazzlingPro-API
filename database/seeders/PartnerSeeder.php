<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Bank Mandiri',
                'slug' => 'bank-mandiri',
                'description' => 'Leading bank in Indonesia',
                'logo_url' => 'https://picsum.photos/150/80?random=1',
                'website_url' => 'https://mandiri.co.id',
                'partner_type' => 'sponsor',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Telkomsel',
                'slug' => 'telkomsel',
                'description' => 'Largest telecommunication company in Indonesia',
                'logo_url' => 'https://via.placeholder.com/150x80?text=Telkomsel',
                'website_url' => 'https://telkomsel.com',
                'partner_type' => 'sponsor',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pertamina',
                'slug' => 'pertamina',
                'description' => 'State-owned oil and gas company',
                'logo_url' => 'https://via.placeholder.com/150x80?text=Pertamina',
                'website_url' => 'https://pertamina.com',
                'partner_type' => 'sponsor',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'BCA',
                'slug' => 'bca',
                'description' => 'One of the largest banks in Indonesia',
                'logo_url' => 'https://via.placeholder.com/150x80?text=BCA',
                'website_url' => 'https://bca.co.id',
                'partner_type' => 'sponsor',
                'status' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Indofood',
                'slug' => 'indofood',
                'description' => 'Leading food company in Indonesia',
                'logo_url' => 'https://via.placeholder.com/150x80?text=Indofood',
                'website_url' => 'https://indofood.com',
                'partner_type' => 'vendor',
                'status' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Astra',
                'slug' => 'astra',
                'description' => 'Automotive and financial services company',
                'logo_url' => 'https://via.placeholder.com/150x80?text=Astra',
                'website_url' => 'https://astra.co.id',
                'partner_type' => 'vendor',
                'status' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
