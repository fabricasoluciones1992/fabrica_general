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
        $user = Auth::id();
        DB::statement("CALL new_register('" . addslashes($new_description) . "', $new_typ_id,$project_id, $user)");
    }
}
