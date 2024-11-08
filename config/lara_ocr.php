<?php

return [
    'api_key' => env('LARAOCR_API_KEY', 'K87303919988957'),

    //Define which OCR engine to use
    'ocr_engine' => 'tesseract',

    //Available OCR engines and their configuration
    'engines' => [
        'tesseract' => [
            'class' => 'Tesseract',
            'executable' => 'tesseract',
        ],
    ],
];
