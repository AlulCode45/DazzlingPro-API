<?php
use App\Models\PageSection;
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function upsert($key, $title, $content, $isActive = true) {
  PageSection::updateOrCreate(['key' => $key], [
    'key' => $key,
    'title' => $title,
    'content' => $content,
    'is_active' => $isActive,
  ]);
}

upsert('philosophy', 'Our Philosophy', [
  'label' => 'Our Philosophy',
  'headline_lines' => ['Event', 'is an', 'Art'],
  'description' => 'At Dazzling Pro, we believe every event is a canvas waiting to be transformed into a masterpiece. Our philosophy is rooted in the conviction that experiences should evoke emotion, inspire wonder, and leave lasting impressions.',
  'pillars' => [
    ['title' => 'Creativity', 'description' => 'Pushing boundaries to deliver unique, unforgettable concepts.'],
    ['title' => 'Precision', 'description' => 'Meticulous attention to every detail, from vision to execution.'],
    ['title' => 'Imagination', 'description' => 'Transforming dreams into tangible, breathtaking realities.'],
    ['title' => 'Emotion', 'description' => 'Creating moments that resonate deep within the soul.'],
  ],
]);

upsert('awards', 'Recognition', [
  'badge' => 'Recognition',
  'headline' => 'The Most Trusted Industry Creative',
  'award_title' => 'ASEAN Trusted Award 2024',
  'award_date' => 'Awarded on January 27, 2024',
]);

upsert('legality', 'Company Legality', [
  'badge' => 'Legal & Compliance',
  'headline' => 'Company ',
  'headline_emphasis' => 'Legality',
  'description' => 'PT Dazzling Pro Indonesia adalah perusahaan yang terdaftar dan beroperasi secara legal dengan seluruh dokumen perizinan yang lengkap.',
  'documents' => [
    ['title' => 'Akta Pendirian Perusahaan', 'number' => 'No. 45 Tahun 2018', 'description' => 'Notaris Dr. Hendra Wijaya, S.H., M.Kn.', 'status' => 'Verified'],
    ['title' => 'NIB (Nomor Induk Berusaha)', 'number' => '1234567890123', 'description' => 'OSS - Online Single Submission', 'status' => 'Active'],
    ['title' => 'SIUP (Surat Izin Usaha Perdagangan)', 'number' => '503/1234/PK/2018', 'description' => 'Dinas Perindustrian dan Perdagangan', 'status' => 'Valid'],
    ['title' => 'NPWP Perusahaan', 'number' => '01.234.567.8-901.000', 'description' => 'Direktorat Jenderal Pajak', 'status' => 'Active'],
    ['title' => 'Sertifikat ISO 9001:2015', 'number' => 'QMS-2023-001234', 'description' => 'Quality Management System', 'status' => 'Certified'],
  ],
]);

upsert('timeline', 'Our Story', [
  'badge' => 'Our Story',
  'headline' => 'Journey Through ',
  'headline_emphasis' => 'Time',
  'events' => [
    ['year' => '2018', 'title' => 'Foundation', 'description' => 'Dazzling Pro was born'],
    ['year' => '2019', 'title' => 'First Major Event', 'description' => 'Corporate gala success'],
    ['year' => '2020', 'title' => 'Digital Pivot', 'description' => 'Virtual event innovation'],
    ['year' => '2021', 'title' => 'Team Expansion', 'description' => 'Growing our creative family'],
    ['year' => '2022', 'title' => 'National Recognition', 'description' => '50+ events delivered'],
    ['year' => '2023', 'title' => 'International Stage', 'description' => 'Thengul Festival launch'],
    ['year' => '2024', 'title' => 'ASEAN Award', 'description' => 'Industry recognition'],
    ['year' => '2025', 'title' => 'The Future', 'description' => 'New horizons await'],
  ],
]);

echo "Page sections seeded: philosophy, awards, legality, timeline\n";