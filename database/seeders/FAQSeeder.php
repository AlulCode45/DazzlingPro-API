<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // FAQs
        $faqs = [
            [
                'question' => 'What types of events do you organize?',
                'answer' => 'We organize a wide range of events including corporate events, concerts, festivals, weddings, product launches, and private parties. Our team specializes in creating unique and memorable experiences tailored to your specific needs.',
                'category' => 'Services'
            ],
            [
                'question' => 'How far in advance should we book your services?',
                'answer' => 'We recommend booking our services at least 3-6 months in advance for large-scale events and 1-2 months for smaller events. This allows us adequate time to plan and execute your event to perfection.',
                'category' => 'Booking'
            ],
            [
                'question' => 'Do you work with a specific budget range?',
                'answer' => 'We work with clients across various budget ranges. During our initial consultation, we\'ll discuss your budget requirements and create a customized proposal that maximizes value while delivering exceptional results.',
                'category' => 'Pricing'
            ],
            [
                'question' => 'Can you handle events outside of Jakarta?',
                'answer' => 'Yes, we organize events throughout Indonesia and internationally. Our team has experience managing events in various locations and can coordinate all logistics regardless of the venue.',
                'category' => 'Services'
            ],
            [
                'question' => 'What makes Dazzling Pro different from other event organizers?',
                'answer' => 'We combine creative vision with meticulous execution. Our team brings artistic innovation to every project while ensuring every detail is perfectly executed. We\'re known for our personalized approach and commitment to exceeding client expectations.',
                'category' => 'About Us'
            ]
        ];

        foreach ($faqs as $index => $faq) {
            FAQ::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'category' => $faq['category'],
                'is_active' => true,
                'sort_order' => $index
            ]);
        }

        $this->command->info('FAQs seeded successfully!');
    }
}