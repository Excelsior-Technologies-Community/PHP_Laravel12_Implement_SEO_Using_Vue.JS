<?php

return [

    'site_name' => env('APP_NAME', 'Laravel'),

    'separator' => ' | ',

    'defaults' => [

        'title' => '{name} - Buy {name} Online at {site}',
        'description' => 'Discover {name}, the best {size} product at the lowest price. Shop now for high quality and fast delivery.',
        'keywords' => 'products, shop, online shopping, deals',
        'canonical' => null,
        'meta_robots' => 'index,follow',

        'og_title' => '{name} - {site}',
        'og_description' => 'Check out {name} at {site}. Best price and quality assured.',
        'og_type' => 'product',
        'og_locale' => 'en_US',

        'twitter_card' => 'summary_large_image',
        'twitter_title' => '{name}',
        'twitter_description' => 'Shop {name} online at {site}.',
    ],

    'limits' => [
        'title' => 60,
        'description' => 160,
        'og_title' => 60,
        'og_description' => 110,
    ],

    'sitemap' => [
        'limit' => 1000,
        'cache' => true,
    ],

    'fallback_image' => env('APP_URL') . '/images/default-og-image.jpg',

];
