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

Route::middleware('api', 'throttle:1000,1')->group(function () {
    Route::post('/gs2c/v3/gameService', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticplayMixed'])->name('pragmaticplayMixed');
    Route::post('/gs2c/ge/v4/gameService', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticplayMixed'])->name('pragmaticplayMixed');

    Route::post('/gs2c/saveSettings.do', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticplaySettingsStateCurl'])->name('savesettings');
    Route::get('/gs2c/reloadBalance.do', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticplayBalanceOnly'])->name('reloadbalance');


    Route::get('/gs2c/announcements/unread', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticplayUnreadAnnouncements'])->name('pragmaticplayUnreadAnnouncements');

    Route::get('/gs2c/promo/active', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticPlayPromotions'])->name('pragmaticplayPromotions');
    Route::get('/gs2c/minilobby/games', [\Westreels\WestreelsMain\GameControllers\PragmaticPlay\GameCallbacksPragmaticPlay::class, 'pragmaticPlayMiniLobbyGames'])->name('pragmaticPlayMiniLobbyGames');

});







Route::get('/testprag', '\Westreels\WestreelsMain\GameControllers\PragmaticPlay\StartSessionPragmaticPlay@pragmaticplayTestSession');
 
Route::get('/dev/hashmacCreate', '\Westreels\WestreelsMain\Gate\GateFunctions@hashmacCreate');
