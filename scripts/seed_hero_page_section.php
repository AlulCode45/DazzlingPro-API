<?php
use App\Models\PageSection;
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payload = [
  'key' => 'hero',
  'title' => 'Crafting Artistic & Memorable Experiences',
  'content' => [
    'title' => 'Crafting Artistic & Memorable Experiences',
    'subtitle' => 'Since 2018',
    'description' => 'Turning Ideas Into Experiences  Where creativity meets precision, and every event becomes a masterpiece.',
    'primary_button_text' => 'Create Your Event With Us',
    'primary_button_url' => '#contact',
    'secondary_button_text' => 'View Our Work',
    'secondary_button_url' => '#portfolio',
    'background_image' => '',
    'overlay_opacity' => 0.5,
    'text_color' => '#ffffff',
    'button_style' => 'default',
    'animation_type' => 'fade',
  ],
  'is_active' => true,
];

PageSection::updateOrCreate(['key' => 'hero'], $payload);

echo "Hero page section upserted.\n";