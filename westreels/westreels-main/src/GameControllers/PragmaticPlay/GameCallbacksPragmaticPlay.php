<?php
namespace Westreels\WestreelsMain\GameControllers\PragmaticPlay;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Westreels\WestreelsMain\Models\Gamesessions;
use App\Models\User;
use Westreels\WestreelsMain\Gate\CallbackFunctions;
use Westreels\WestreelsMain\GameControllers\PragmaticPlay\SideFunctions;
class GameCallbacksPragmaticPlay extends \Westreels\WestreelsMain\Gate\CallbackFunctions
{

    public function pragmaticCurlRespin($data)
    {
        $url = "https://demogamesfree.ppgames.net/gs2c/v3/gameService";
        $response = Http::retry(3, 500, function ($exception, $request) {
                return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;
    }

    public static function pragmaticPlayMiniLobbyGames(Request $request)
    {
        return SideFunctions::miniLobbyGames();
    }

    public static function pragmaticplayUnreadAnnouncements(Request $request)
    {
        return SideFunctions::announcements();
    }


    public function pragmaticplayPromotions(Request $request)
    {   
$header = Http::withHeaders([
    'Cookie' => '_casino_session=a26bc208e69a85e0e98531ed442898fd; original_user_id=MTIzMjA%3D--68f38d06e3ad27e0a50c4c375a7a0922cd95c549; _ga=GA1.1.2055249889.1652431980; _ga_GWC3ZK8F5X=GS1.1.1652471918.4.1.1652475687.59; dateamlutsk-_zldp=M6KbIcofZ5ONG%2BYTpymMRROLAd2abMC5K%2Fpr6oin0qKdpN59%2B4jS0upz5MFvdeC24V9u95m%2BMKY%3D; dateamlutsk-_zldt=8c357ff7-8693-4cd0-a1df-84942c3082b2-0; locale=ImVuIg%3D%3D--faa52eee2a616938ef2a4bf113bd5f0e77a9168a',
    'Host' => 'api.bets.io',
    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.2 Safari/605.1.15',
])->get('http://104.22.2.150/games/pragmaticexternal/WolfGold/29625');


return $header; 
        $gamesymbol = $request['symbol'];
        $url = 'https://softswiss.pragmaticplay.net/gs2c/promo/active/?symbol='.$gamesymbol.'&mgckey=AUTHTOKEN@690becbd04533146cffd8a281161dafb8f50329f04e76c06c62bfc8480357bd6~stylename@sfws_betssw~SESSION@e39adeb';
        $data = '';
        $response = Http::retry(1, 200, function ($exception, $request) {
                return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;
    }
    
    public function pragmaticCurlRequest(Request $request)
    {
        $urlFullUrl = $request->fullUrl();
        $urlReplaceToReal = str_replace(env('APP_URL'), 'https://demogamesfree.ppgames.net', $urlFullUrl);
        $url = $urlReplaceToReal;
        $data = $request->getContent();

        $response = Http::retry(3, 500, function ($exception, $request) {
                return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;
    }

    public function oldcurl(Request $request)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 2000);

        $headers = array(
           "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0",
           "Accept: */*",
           "Content-Type: application/x-www-form-urlencoded",
           "Origin: https://demogamesfree.ppgames.net",
           "Referer: https://demogamesfree.ppgames.net/",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = $request->getContent();

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);

        curl_close($curl);

    }

    public function pragmaticplaySettingsStateCurl(Request $request) 
    {

        $url = 'https://demogamesfree.pragmaticplay.net/gs2c/saveSettings.do';
        $data = $request->getContent();

        $response = Http::retry(1, 1000, function ($exception, $request) {
                return $exception instanceof ConnectionException;
        })->withBody(
            $data, 'application/x-www-form-urlencoded'
        )->post($url);

        return $response;

    }

    public function pragmaticplayBalanceOnly(Request $request)
    {   
        $realToken = $request['mgckey'];
        $getSession = GameSessions::where('token_original', $realToken)->first();

        if($getSession) {
            $balanceFinal = self::generalizedBalanceCall($getSession->player_id, $getSession->currency) / 100;
            return 'balance='.$balanceFinal;
        }
    }

    public function pragmaticplayMixed(Request $request)
    {   
    
        // Select session id from request, not sure if this is wrong way cause 'isset' on query is php heavy
        if(isset($request['mgckey'])) {
            $realToken = $request['mgckey'];
        } elseif(isset($request['id'])) {
            $realToken = $request['id'];
        } else {
            return $request;
        }
        $getSession = GameSessions::where('token_original', $realToken)->first();
        if($getSession) {
        
        //Execution time, for delaying return if we're too fast (before spin lands on front)
        $time_start = microtime(true);

        //Curl forward to pragmatic server
        $query_string = self::pragmaticCurlRequest($request);

        parse_str($query_string, $q_arr);
        $balanceCallNeeded = true;


        if(isset($q_arr['c'])) {
            if($q_arr['na'] === 's') {
                $betAmount = $q_arr['c'] * $q_arr['l'] * 100;
                $balanceCallNeeded = false;
                $sendBetSignal = self::generalizedBetCall($getSession->player_id, $getSession->currency, $getSession->extra_meta, $betAmount, 0);
                if(is_numeric($sendBetSignal)) {
                    $balanceFinal = $sendBetSignal / 100;
                } else {

                    //Insufficient Balance Error
                    if($sendBetSignal->status() === 402) {
                        $q_arr['balance'] = -1;
                        $q_arr['balance_cash'] = -1;
                        $resp = http_build_query($q_arr);
                        $resp = urldecode($resp);
                        return '-1&balance=-1&balance_cash=-1';
                    } else {
                        Log::notice('Unknown bet processing error occured: '.$request);
                        return 'unlogged';
                    }
                }
            }
        }

        if(isset($q_arr['w'])) {
            $selectWinArgument = $q_arr['w'];
            $winRaw = floatval($selectWinArgument);
            if($winRaw !== '0.00') {

                // Respin on big win, this should be set in presets most likely in database
                // This respin is only done once, that means there is still a chance the respin triggers another win (while unlikely)
                if($winRaw > '2122121.00') {
                    $data = $request->getContent();
                    parse_str($data, $q_arr_request);
                    $q_arr_request['counter'] = $q_arr_request['counter'] + 2;
                    $q_arr_request['index'] = $q_arr_request['index'] + 1;
                    $resp = http_build_query($q_arr_request);
                    $resp = urldecode($resp);            
                    $query_string = self::pragmaticCurlRespin($resp);
                    parse_str($query_string, $q_arr);

                    if(isset($q_arr['w'])) {
                        $selectRespinWin = $q_arr['w'];
                        if(is_numeric($selectRespinWin)) {
                            $winAmount = $selectRespinWin * 100;
                            $balanceFinal = self::generalizedBetCall($getSession->player_id, $getSession->currency, $getSession->extra_meta, 0, $winAmount) / 100;
                        } else { 
                            $balanceFinal = self::generalizedBalanceCall($getSession->player_id, $getSession->currency) / 100;
                        }
                        $balanceCallNeeded = false;
                    }
                } else { 
                $winAmount = $selectWinArgument * 100;
                $balanceFinal = self::generalizedBetCall($getSession->player_id, $getSession->currency, $getSession->extra_meta, 0, $winAmount) / 100;
                $balanceCallNeeded = false;
                }
            }
        }

        if($balanceCallNeeded === true) {
            $balanceFinal = self::generalizedBalanceCall($getSession->player_id, $getSession->currency) / 100;
        }

        $q_arr['balance'] = $balanceFinal;
        $q_arr['balance_cash'] = $balanceFinal;

        //generate new query string
        $resp = http_build_query($q_arr);
        $resp = urldecode($resp);
        
        $timestart = number_format(microtime(true) - $time_start, 4);

        if($timestart < config('app.game_delay_trigger')) {
            $delayAdd = (int) config('app.game_delay_extra') * 1000000;
            usleep($delayAdd); // 0.1s sleep/delay added to game if under 0.3s
        }

        return $resp;


        } else {
            return 'unlogged';
            die;
        }
    }

}