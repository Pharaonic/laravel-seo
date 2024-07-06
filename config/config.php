<?php

return [
    /**
     * Default values of SEO Tages.
     * A list of default values for SEO Tags.
     */
    'default' => [
        'charset' => 'UTF-8',
        // 'viewport' => 'width=device-width, initial-scale=1.0',

        'open-graph' => [
            'type' => 'website',
            // 'site_name' => 'Site Name',
        ],

        'twitter' => [
            'card' => 'summary',
            // 'site' => '@site',
            // 'creator' => '@creator',
        ],
    ],

    /**
     * SEO Elements.
     * A list of SEO Elements that you can use in your application.
     */
    'elements' => [
        // 'charset' => \Pharaonic\Laravel\SEO\Elements\Charset2::class,
    ],
];
