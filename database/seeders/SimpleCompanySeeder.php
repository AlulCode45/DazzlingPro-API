<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyInformation;

class SimpleCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Company Information
        CompanyInformation::create([
            'company_name' => 'Dazzling Pro Organizer',
            'tagline' => 'Serving With Heart, Driven By Passion, Guided By Professionalism',
            'description' => 'Dazzlingpro adalah perusahaan event dan wedding organizer yang berfokus pada industri kreatif, menghadirkan ide-ide segar, konsep inovatof, dan eksekusi yang memukau untuk setiap acara.\n\nKami percaya bahwa sebuah event bukan sekadar agenda, melainkan sebuah karya seni yang mampu mambangun citra, menghubungkan emosi, dan menciptakan kenangan mendalam bagi setiap audiens. \n\nBerdiri dari semangat untuk "mengubah ide menjadi pengalaman", kami memadukan imajinasi kreatif, perencanaan stategis, dan eksekusi presisi dalam setiap proyek. Setiap detail kami perhatikan dengan cermat, karena kami tahu bahwa kesuksesan sebuah acara terletak pada harmoni antara konsep dan pelaksanaannya.',
            'email' => 'dazzlingpro.org@gmail.com',
            'phone' => '0812-3964-0057',
            'whatsapp' => '0812-3964-0057',
            'address' => 'Graha Pena Office Building, Gedung Extension, lantai 8, \nunit 803, unit office 01, Jalan Ahmad Yani No 88 \nSurabaya , jawa timur 60231.',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60231',
            'country' => 'Indonesia',
            'website_url' => 'https://dazzlingpro.co.id/',
            'about_us' => 'Kami hadir untuk menjawab segala kebutuhan acara Anda dengan pendekatan yang kreatif dan detail-oriented. Berbekal pengalaman luas, kami siap menghidupkan Festival & Pameran Kreatif yang memadukan hiburan, seni, serta interaksi publik dalam satu ruang yang harmonis. Bagi pecinta seni, kami mampu mengkurasi Konser & Pertunjukan Musik, mulai dari suasana akustik yang intim hingga konser skala besar yang tak terlupakan.\n\nDi dunia profesional, kami mengemas Corporate Event seperti seminar, gathering, atau award night dengan estetika yang modern. Kami juga ahli dalam Product Launch & Brand Activation, mengubah setiap peluncuran produk menjadi momen spektakuler yang melekat kuat di benak konsumen. Selain itu, kesan pertama yang mendalam adalah komitmen kami dalam menangani Event Opening yang impactful dan memorable.\n\nTerakhir, untuk semangat kebersamaan dan kesehatan, kami menyelenggarakan berbagai Event Olahraga dan kompetisi dengan pengelolaan profesional yang menjamin pengalaman seru bagi setiap peserta. Kami pun menyediakan ruang untuk bertumbuh melalui Workshop & Creative Talkshow yang edukatif namun tetap inspiratif. \n\nBagi Anda yang menginginkan sentuhan personal, layanan Wedding & Private Celebration kami hadirkan secara eksklusif sesuai karakter dan impian Anda.',
            'vision' => 'Menjadi event dan wedding organizer kreatif terdepan di Indonesia yang mampu menciptakan acara dengan nilai artistik, inovasi, dan dampak positif bagi brand, komunitas, dan masyarakat luas.',
            'mission' => '- Menciptakan konsep yang unik, relevan, dan berkesan \n- Menggabungkan kreativitas dengan teknologi untuk meningkatkan pengalaman audiens\n- Memberdayakan talenta kreatif lokal untuk berkolaborasi dalam berbagai proyek\n- Menyediakan layanan yang profesional, tepat waktu, dan berkualitas tinggi',
            'core_values' => 'Konsep Unik dan Personal setiap event kami rancang berdasarkan identitkas dan tujuan brand/klien. Eksekusi profesional yang dikerjakan oleh tim berpengalaman dengan standar kerja yang rapi dan tepat waktu. Jaringan luas terhubung dengan vendor, talent, dan komunitas kreatif di seluruh Indonesia, serta fleksibilitas dan inovasi adaptif terhadap tren dan mampu menciptakan ide yang luar biasa.',
            'google_maps_url' => 'https://share.google/qzdiC918boYbGmYgE',
            'business_registration_number' => '9120107390812',
            'bank_account' => '001101004275309',
            'bank_name' => 'BRI (a.n CV Syarfaa Jaya)',
            'social_media' => json_encode([
                ['platform' => 'instagram', 'url' => 'https://instagram.com/dazzlingpro'],
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/dazzlingpro'],
                ['platform' => 'youtube', 'url' => 'https://youtube.com/@dazzlingpro']
            ]),
            'operating_hours' => json_encode([
                ['day' => 'Senin', 'hours' => '09:00 - 17:00'],
                ['day' => 'Selasa', 'hours' => '09:00 - 17:00'],
                ['day' => 'Rabu', 'hours' => '09:00 - 17:00'],
                ['day' => 'Kamis', 'hours' => '09:00 - 17:00'],
                ['day' => 'Jumat', 'hours' => '09:00 - 17:00'],
                ['day' => 'Sabtu', 'hours' => '09:00 - 15:00'],
                ['day' => 'Minggu', 'hours' => 'Tutup']
            ]),
            'seo_meta' => json_encode([
                'title' => 'Partner Kreatif untuk Event Luar Biasa & Berkesan',
                'description' => 'Event Organizer Profesional dengan Konsep Unik, Eksekusi Presisi, dan Jaringan Luas di Seluruh Indonesia.',
                'keywords' => 'Event Organizer Surabaya, EO Profesional, Jasa Event Organizer Surabaya, Event Organizer Bojonegoro, Creative Company, Wedding Organizer Bojonegoro, Wedding Organizer Surabaya, Vendor Oranizer Nasional Indonesia'
            ]),
            'is_active' => true
        ]);

        $this->command->info('Company information seeded successfully!');
    }
}