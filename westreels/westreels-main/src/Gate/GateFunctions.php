<?php
namespace Westreels\WestreelsMain\Gate;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GateFunctions extends \App\Http\Controllers\Controller
{

    public function mainGate(Request $request)
    {
        
        
        return time();
    }


}
 
