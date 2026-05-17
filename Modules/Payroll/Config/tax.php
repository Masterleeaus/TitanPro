<?php

return [
    'default_country' => env('PAYROLL_TAX_COUNTRY', 'AU'),
    'bands' => [
        'AU' => [
            ['from' => 0, 'to' => 18200, 'rate' => 0.0, 'base' => 0, 'label' => 'tax-free threshold'],
            ['from' => 18201, 'to' => 45000, 'rate' => 0.16, 'base' => 0, 'label' => 'low income band'],
            ['from' => 45001, 'to' => 135000, 'rate' => 0.30, 'base' => 4288, 'label' => 'standard band'],
            ['from' => 135001, 'to' => 190000, 'rate' => 0.37, 'base' => 31288, 'label' => 'high income band'],
            ['from' => 190001, 'to' => null, 'rate' => 0.45, 'base' => 51638, 'label' => 'top band'],
        ],
    ],
];
