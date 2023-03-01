<?php

return [
    [
        'key'    => 'sales.paymentmethods.payu',
        'name'   => 'Payu',
        'sort'   => 6,
        'fields' => [
            [
                'name'          => 'title',
                'title'         => 'admin::app.admin.system.title',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ], [
                'name'          => 'description',
                'title'         => 'admin::app.admin.system.description',
                'type'          => 'textarea',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'payu_pos_id',
                'title'         => 'admin::app.admin.system.payu-pos-id',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
			[
                'name'          => 'payu_second_key',
                'title'         => 'admin::app.admin.system.payu-second-key',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'          => 'payu_client_secret',
                'title'         => 'admin::app.admin.system.payu-client-secret',
                'type'          => 'text',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ],
            [
                'name'    => 'payu-website',
                'title'   => 'admin::app.admin.system.payu-websitestatus',
                'type'    => 'select',
                'options' => [
                    [
                        'title' => 'Sandbox',
                        'value' => 'Sandbox',
                    ], [
                        'title' => 'Live',
                        'value' => 'DEFAULT',
                    ],
                ],
            ],
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true,
            ]
        ]
    ]
];