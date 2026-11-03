<?php

return [
    /**
     * Default values of the SEO tags.
     */
    'default' => [
        'charset' => 'UTF-8',
        // 'viewport' => 'width=device-width, initial-scale=1.0',

        'open_graph' => [
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
     * SEO elements.
     * Extra or replacement elements, each extending SingleElement or MultipleElement.
     */
    'elements' => [
        // 'charset' => \App\SEO\Charset::class,
    ],
];
