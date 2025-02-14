<?php

return [
    'mode' => 'utf-8',
    'format' => 'A4',
    'author' => 'INJAZ Academy',
    'subject' => 'INJAZ Academy - Certificate',
    'keywords' => '',
    'creator' => 'INJAZ Academy',
    'display_mode' => 'fullpage',
    'tempDir' => base_path('../temp/'),
    'pdf_a' => false,
    'pdf_a_auto' => false,
    'icc_profile_path' => '',
    'font_path' => storage_path('/fonts/'),
    'font_data' => [
        'arabicfont' => [
            'R' => 'KFGQPC Uthmanic Script HAFS Regular.ttf', // regular font
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
    ],
];
