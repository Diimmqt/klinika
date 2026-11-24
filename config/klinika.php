<?php

return [
    'contact' => [
        'email' => env('KLINIKA_CONTACT_EMAIL'),
        'phone' => env('KLINIKA_CONTACT_PHONE', ''),
        'address' => env('KLINIKA_CONTACT_ADDRESS', ''),
        'hours' => env('KLINIKA_CONTACT_HOURS', 'Senin – Jumat, 08.00 – 17.00 WIB'),
    ],
];
