<?php

use Illuminate\Support\Facades\Route;
use Westreels\WestreelsMain;
use Illuminate\Http\Request;
use Spatie\SslCertificate\SslCertificate;

Route::group(['middleware' => ['api']], function () {
        Route::post('/player/create', '\Westreels\WestreelsMain\Gate\PlayerGateController@playerLogin');
});


// Auth test
Route::middleware(['auth:sanctum'])->group(function () {
	Route::get('/indextest', 'WestreelsMain@indexTest');
});


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
