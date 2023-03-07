<?php

return [
    'bagistoinpostshipping' => [
        'code'         => 'bagistoinpostshipping',
        'title'        => 'Paczkomaty Inpost',
        'description'  => 'Paczkomaty',
        'active'       => true,
        'default_rate' => '8,99',
        'type'         => 'per_unit',
        'class'        => 'Emsit\BagistoInPostShipping\Carriers\BagistoInPostShipping',
    ],
];