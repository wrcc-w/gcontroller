<?php

use Illuminate\Support\Facades\Route;
use Westreels\WestreelsMain;
use Illuminate\Http\Request;

Route::group(['middleware' => ['api']], function () {
        Route::post('/player/login', '\Westreels\WestreelsMain\Gate\PlayerGateController@playerLogin');

Route::get('/dev/pragmaticplaySessionStart', '\Westreels\WestreelsMain\GameControllers\PragmaticPlay\StartSessionPragmaticPlay@pragmaticplaySessionStart');

        
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/teete', function () {  
        return 'test';
    });
});



Route::get('/testprag', '\Westreels\WestreelsMain\GameControllers\PragmaticPlay\StartSessionPragmaticPlay@pragmaticplayTestSession');
 



Route::get('/dev/hashmacCreate', '\Westreels\WestreelsMain\Gate\GateFunctions@hashmacCreate');



Route::get('/westreelssl', function () {  



       $response = \Illuminate\Support\Facades\Http::withOptions([
            'cert' => '/home/ploi/gamecontroller.westreels.com/csr_name.pem',
            'ssl_key' => '/home/ploi/gamecontroller.westreels.com/example.key'
        ])->post('https://gamecontroller.westreels.com/westreelsslEndpoint');

        return $response;

});


Route::post('/westreelsslEndpoint', function (Request $request) {  

dd($request);

});
