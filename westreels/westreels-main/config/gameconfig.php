<?php
// config for Westreels/WestreelsMain/gameconfig
return [


    /*
    |--------------------------------------------------------------------------
    | Application Casino Settings
    |--------------------------------------------------------------------------
    | Storing env values here so we can later easily enable roadrunner cache (www.roadrunner.dev)
    */

    /* Server ip used for internal router middleware */
    'server_ip' => env('APP_SERVER_IP', '127.0.0.1'),

    /* Absolute Filepath location of the westreels main package */
    'package_main_path' => '/home/ploi/gamecontroller.westreels.com/westreels/westreels-main',
    'raw_db_storage' => '/home/ploi/gamecontroller.westreels.com/westreels/westreels-main/database/raw_database_storage',

    /*
    |--------------------------------------------------------------------------
    | Cache Settings for Casino Functions
    |--------------------------------------------------------------------------
    | In development areas, set the APP_ENV to local in .env - this will disable the caching, while you can leave value below like you wish for production
    */

    'data_cache' => [
		'cache_gamelist_length' => 15,
		'cache_presets_length' => 10,
    ],


    /*
    |--------------------------------------------------------------------------
    | Pragmatic Specific Settings
    |--------------------------------------------------------------------------
    |
    | Game delays are in seconds, set to 0.00 to disable - please note that adding extra delay should only be done on low loads to emulate regular casino play. 
    | Setting 'device_url' is for sideloading extra content, if not using simply set to your staitc proxy URL.
    | Proxy static url should be rewriting with caching in nginx to pragmatic.
    */

    'pragmaticplay' => [
        'static_proxy_url' => 'https://game-assets-proxy.pragmaticgaming.net/static_pragmatic/',
        'device_url' => 'game-assets-proxy.pragmaticgaming.net',
        'api_url' => 'https://gamecontroller.westreels.com',
        'game_delay_trigger' => '0.00',
    	'game_delay_extra' => '4.3',
    ],

];
