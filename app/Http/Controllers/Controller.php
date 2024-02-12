<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function NewRegisterTrigger($new_description,$new_typ_id, $proj_id)
    {
        $project_id = ($proj_id === null) ? env('APP_ID'): $proj_id;
        $trigger = "CALL new_register('" . addslashes($new_description) . "', $new_typ_id,$project_id,1)";
        DB::statement($trigger);
    }

    function auth(){
        session_start();
        if (isset($_SESSION['api_token'])) {
            $token = $_SESSION['api_token'];
            $use_id = $_SESSION['use_id'];
            $proj_id = $_SESSION['proj_id'];
            return [
                "token" => $token,
                "use_id" => $use_id,
                "proj_id" => $proj_id
            ];
        } else {
            return  'Token not found in session';
        }
    }
}
