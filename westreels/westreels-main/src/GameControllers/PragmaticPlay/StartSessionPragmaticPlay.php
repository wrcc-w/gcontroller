<?php
namespace Westreels\WestreelsMain\GameControllers\PragmaticPlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Westreels\WestreelsMain\Models\Gamesessions;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StartSessionPragmaticPlay
{
    public function pragmaticplayTestSession(Request $request)
    {
        $selectGame = DB::table('gamelist_pragmatic')->where('game_id', $request->game_id)->first();
        $strLowerProvider = strtolower($selectGame->provider);
        $buildArray = array(
            'game_id' => $selectGame->game_id,
            'game_name' => $selectGame->fullName,
            'api_origin_id' => $selectGame->api_origin_id,
            'api_extension' => $selectGame->api_extension,
            'provider' => $strLowerProvider,
            'player' => User::get()->random('1'),
            'currency' => 'USD', // should be in request
            'mode' => 'real', //should be in request demo/real money play
            'method' => 'gameRequestByPlayer',
        );
        return Http::timeout(5)->get(config('app.url').'/dev/pragmaticplaySessionStart', $buildArray);


    }

    public function pragmaticplaySessionStart(Request $request)
    {   

        $fullContent = $request;

        //Check if existing internal session is available
        $getInternalSession = Gamesessions::where('game_id', $fullContent->game_id)->where('player_id', $fullContent->player)->where('currency', $fullContent->currency)->where('created_at', '>', Carbon::now()->subMinutes(45))->first();
        $newSession = false;

        if(!$getInternalSession) {
            $createInternalSession = Gamesessions::create([
                'game_id' => $fullContent->game_id,
                'token_internal' => Str::uuid(),
                'token_original' => '0',
                'currency' => $fullContent->currency,
                'extra_meta' => $request->api_origin_id,
                'expired_bool' => 0,
                'games_amount' => 0,
                'player_id' => $fullContent->player,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $newSession = true;
            $getInternalSession = $createInternalSession;
        }


        if($newSession === true) {

        $compactSessionUrl = "https://demogamesfree.pragmaticplay.net/gs2c/openGame.do?gameSymbol=".$request->api_origin_id."&websiteUrl=https%3A%2F%2Fdemogamesfree.pragmaticplay.net&jurisdiction=99&lobby_url=https%3A%2F%2Fwww.pragmaticplay.com%2Fen%2F&lang=EN&cur=".$request->currency."";

        $ch = curl_init($compactSessionUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $redirectURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);

        $launcherTest = Http::withOptions([
            'verify' => false,
        ])->get($redirectURL);

        $parts = parse_url($redirectURL);
        parse_str($parts['query'], $query);

            $createInternalSession->update([
                'token_original' => $query['mgckey']
            ]);


        } else {
            $redirectURL = 'https://demogamesfree.pragmaticplay.net/gs2c/html5Game.do?extGame=1&mgckey='.$getInternalSession->token_original.'&symbol='.$request->api_origin_id.'&jurisdictionID=99';
            $launcherTest = Http::withOptions([
                'verify' => false,
            ])->get($redirectURL);
        }



        $replaceAPItoOurs = str_replace('/operator_logos/',  '', $launcherTest);
        $replaceAPItoOurs = str_replace('"datapath":"https://demogamesfree.pragmaticplay.net/gs2c/common/games-html5/games/vs/',  '"datapath":"'.config('gameconfig.pragmaticplay.static_proxy_url'), $replaceAPItoOurs);
        //$replaceAPItoOurs = str_replace('"/gs2c',  '"/api/gs2c', $replaceAPItoOurs);
        $replaceAPItoOurs = str_replace('"https://demogamesfree.pragmaticplay.net/gs2c/ge/v4/gameService',  '"https://'.config('gameconfig.pragmaticplay.api_url').'/api/gs2c/ge/v4/gameService', $replaceAPItoOurs);
        $deviceURL = str_replace('https://', '', config('app.url'));
        $replaceAPItoOurs = str_replace('device.pragmaticplay.net',  $deviceURL, $replaceAPItoOurs);
        $replaceAPItoOurs = str_replace('demoMode":"1"',  'demoMode":"0"', $replaceAPItoOurs);

        $replaceAPItoOurs = str_replace('/gs2c/v3/gameService',  '/api/gs2c/v3/gameService', $replaceAPItoOurs);



        $finalLauncherContent = $replaceAPItoOurs;

        return view('launcher')->with('content', $finalLauncherContent);

    }
}
