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

    public function NewRegisterTrigger($new_description,$new_typ_id,$use_id)
    {
        $trigger = "CALL new_register('" . addslashes($new_description) . "', $new_typ_id,6,$use_id)";
        DB::statement($trigger);
    }

    public function validate_exists($data, $table, $column, $PK, $pk){
        $values = DB::table($table)->get([$PK, $column]);
        foreach ($values as $value) {
            if ($value->$column == $data && $value->$PK != $pk) {
                return 0;
            }
        }
        return 1;
    }
}
