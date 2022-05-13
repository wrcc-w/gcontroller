<?php
// config for Westreels/WestreelsMain/gameconfig
return [


    /*
    |--------------------------------------------------------------------------
    | Application Casino Settings
    |--------------------------------------------------------------------------
    | Storing env values here so we can later easily enable roadrunner cache (www.roadrunner.dev)
    */

    /* Toggle API between local and external, set 'api_key_setting' to 'external' to use external api verification, else the variable is the apikey  */
    'api_key_setting' => 'apikey2020202',
    'api_key_local_var' => [
        'ip_allowed' => '127.0.0.1',
        'balance_url' => 'https://gamecontroller.westreels.com/dev/getBalance',
        'result_url' => 'https://gamecontroller.westreels.com/dev/result',
        'cashier_url' => 'https://gamecontroller.westreels.com/cashier',
    ],


    /* Server ip used for internal router middleware */
    'server_ip' => env('APP_SERVER_IP', '127.0.0.1'),

    /* Generate hashmac for header in API on testing purposes */
    'hashmac_sign_creator' => true,

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
