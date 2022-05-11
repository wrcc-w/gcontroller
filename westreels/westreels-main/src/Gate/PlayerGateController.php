<?php
namespace Westreels\WestreelsMain\Gate;


use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PlayerGateController extends \Westreels\WestreelsMain\Gate\GateFunctions
{

    public function playerLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'player_id' => ['required', 'min:3', 'max:100', 'regex:/^[^(\|\]`!%^&=};:?><’)]*$/'],
            'currency' => ['required', 'min:2', 'max:7'],
            'name' => ['required', 'min:4', 'max:25'],
            'email' => ['required', 'email', 'min:4', 'max:25'],
            'password' => ['required'],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json(['status' => 400, 'error' => 'Validation of request form failed.', 'validation_messages' => $validator->errors(), 'request_ip' => $_SERVER['REMOTE_ADDR']])->setStatusCode(400);
        }

        $hash = hash_hmac('sha256', '42104120210', '5|1652281688');

        $headerApikey = $request->header('x-apikey');
        $headerTimestamp = $request->header('x-time');

        return array('hash' => $hash, 'apikey' => $headerApikey, 'timestamp' => $headerTimestamp);
        $user = User::where('email', $request->email)->first();

        $newUserCreated = false;
        if(!$user) {
            $newUserCreated = true;

            $user = \App\Models\User::create([
                    'name' => $request->name,
                    'password' => bcrypt($request->password),
                    'email' => $request->email,
            ]);
        } 

        $revokePreviousToken = $user->tokens()->delete();
        $token = $user->currentAccessToken('player:auth');

        if(!$token) {
            $token = $user->createToken('player:auth', ['remember']);
        }

        return ['user' => $user, 'token' => $token];
    }


}
 
