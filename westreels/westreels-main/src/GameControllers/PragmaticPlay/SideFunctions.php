<?php
namespace Westreels\WestreelsMain\GameControllers\PragmaticPlay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SideFunctions
{
	public static function miniLobbyGames()
	{
		$lobbyGames = Http::get('https://gamecontroller.westreels.com/minilobbygames.json');
		return $lobbyGames;
	}

	public static function announcements()
	{
		//https://softswiss.pragmaticplay.net/gs2c/announcements/unread/?symbol=vs25wolfgold&mgckey=AUTHTOKEN@b11a28c53c38d3cbd2d0f1af251e4f5ee4f63634191b0807e62292bc4979cf52~stylename@sfws_betssw~SESSION@4055c52c-e6b8-49c9-8692-d2130173be4a

		$announcements = array("error" => 0, "description" => "OK", "announcements" => array());
		return $announcements;
	}
	


}