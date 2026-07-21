<?php
// config/plans.php

return [
    'free' => [
        'name' => 'Trial',
        'price' => 00,
        'currency' => 'INR',
        'features' => [
            ['text' => '10 Members', 'included' => true],
            ['text' => '1 GB storage & Auto Backup', 'included' => true],
            ['text' => '7 Days Trial', 'included' => true],
            ['text' => 'DB & File Backup On Google Drive', 'included' => false],
            ['text' => 'Some feature disabled', 'included' => false],
            ['text' => 'No Support', 'included' => false]
        ],
        'popular' => false,
        'show_button' => false,
        'lines' => 'Trial pupose for single admin with multiple employees upto 10'
    ],
    'starter' => [
        'name' => 'Personal',
        'price' => 99,
        'GSTprice' => 116.82,
        'currency' => 'INR',
        'features' => [
            ['text' => '15 Users', 'included' => true],
            ['text' => '10 GB storage & Auto Backup', 'included' => true],
            ['text' => 'Limited Emails', 'included' => true],
            ['text' => '18 % GST excluded (GST 17.82 INR)', 'included' => true],
            ['text' => 'DB & File Backup On Google Drive', 'included' => false],
            ['text' => 'Limited Support', 'included' => false],
            
        ],
        'popular' => true,
        'show_button' => true,
        'lines' => 'Ideal for small team with basic needs.'
    ],
    'professional' => [
        'name' => 'Enterprise',
        'price' => 999,
        'GSTprice' => 1178.82,
        'currency' => 'INR',
        'features' => [
            ['text' => '25 Users', 'included' => true],
            ['text' => '25 GB storage & Auto Backup', 'included' => true],
            ['text' => 'Limited Emails', 'included' => true],
            ['text' => 'DB & File Backup On Google Drive', 'included' => true],
            ['text' => 'Working Hour Support', 'included' => true],
            ['text' => '18 % GST excluded (GST 179.82 INR)', 'included' => true]
        ],
        'popular' => false,
        'show_button' => true,
        'lines' => 'Ideal for small team with basic needs.'
    ],
    'enterprise' => [
        'name' => 'Business',
        'price' => 1999,
        'GSTprice' => 2358.82,
        'currency' => 'INR',
        'features' => [
            ['text' => '35 Users', 'included' => true],
            ['text' => 'Unlimited storage & Auto Backup', 'included' => true],
            ['text' => 'Unlimited Emails', 'included' => true],
            ['text' => 'DB & File Backup', 'included' => true],
            ['text' => 'Requirement Customization (Dropbox)', 'included' => true],
            ['text' => 'Working Hour Support', 'included' => true],
            ['text' => '18 % GST excluded (GST 359.82 INR)', 'included' => true]
        ],
        'popular' => false,
        'show_button' => true,
        'lines' => 'Ideal for average team with basic needs.'
    ]
];