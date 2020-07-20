<?php

use Illuminate\Support\Str;

$asyncEventConfigPath = sprintf('%s/config/async-event-nav.json', __DIR__);
$coreConfigPath = sprintf('%s/config/core-nav.json', __DIR__);
$httpCorsConfigPath = sprintf('%s/config/http-cors-nav.json', __DIR__);

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'Labrador Kennel',
    'siteDescription' => 'A suite of asynchronous PHP applications powered by Amp',

    'packages' => [
        [
            'name' => 'core',
            'description' => 'Foundation for building Labrador apps and plugins.',
            'docsUrl' => '/docs/core',
            'githubUrl' => 'https://github.com/labrador-kennel/core'
        ],
        [
            'name' => 'http',
            'description' => 'Amp\'s http-server with Labrador and FastRoute layered on top.',
            'githubUrl' => 'https://github.com/labrador-kennel/http'
        ],
        [
            'name' => 'http-cors',
            'description' => 'Amp\'s <em>de facto</em> CORS middleware.',
            'docsUrl' => '/docs/http-cors',
            'githubUrl' => 'https://github.com/labrador-kennel/http-cors'
        ],
        [
            'name' => 'async-event',
            'description' => 'A semantic event system that runs on top of Amp\'s Loop.',
            'docsUrl' => '/docs/async-event',
            'githubUrl' => 'https://github.com/labrador-kennel/async-event'
        ],
        [
            'name' => 'coding-standard',
            'description' => 'Labrador\'s coding style to help format your code. PSR-2 with OTBS.',
            'githubUrl' => 'https://github.com/labrador-kennel/coding-standard'
        ],
        [
            'name' => 'governance',
            'description' => 'How Labrador governs itself. Includes contributing guide and code of conduct.',
            'githubUrl' => 'https://github.com/labrador-kennel/governance'
        ]
    ],
    'navMenus' => [
        'asyncEvent' => json_decode(file_get_contents($asyncEventConfigPath), true),
        'core' => json_decode(file_get_contents($coreConfigPath), true),
        'httpCors' => json_decode(file_get_contents($httpCorsConfigPath), true)
    ],

    // helpers
    'isActive' => function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },
    'isActiveParent' => function ($page, $menuItem) {
        if (is_object($menuItem) && $menuItem->children) {
            return $menuItem->children->contains(function ($child) use ($page) {
                return trimPath($page->getPath()) == trimPath($child);
            });
        }

        return false;
    },
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
];
