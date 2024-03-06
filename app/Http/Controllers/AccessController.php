<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AccessController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $access = DB::select("SELECT access.acc_id,access.acc_status,projects.proj_name, access.use_id,users.use_mail,persons.per_id, persons.per_name,persons.per_document,projects.proj_id FROM access
            INNER JOIN projects ON access.proj_id = projects.proj_id
            INNER JOIN users ON access.use_id = users.use_id
            INNER JOIN persons ON users.use_id = persons.per_id");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Access",4,$proj_id,$use_id);
            return response()->json([
              'status' => true,
                'data' => $access
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
             'status' => false,
             'message' => $th
            ],500);
        }

    }

    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'proj_id' =>'required|integer',
            'use_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
            $acces = DB::table("access")->where('proj_id','=', $request->proj_id)->where('use_id','=', $request->use_id)->first();
            if ($acces != []) {
                return response()->json([
                    'status' => False,
                    'message' =>"This user already has access to this project"
                ]);
            }
            $access = new Access($request->input());
            $access->acc_status = 1;
            $access->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Access: $request->acc_id",3,$proj_id,$use_id);
            return response()->json([
           'status' => True,
            'message' => "The access: ".$access->use_id." has been created."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $access = DB::select("SELECT access.acc_id,access.acc_status,projects.proj_name, access.use_id,users.use_mail,persons.per_id, persons.per_name,persons.per_document,projects.proj_id FROM access
        INNER JOIN projects ON access.proj_id = projects.proj_id
        INNER JOIN users ON access.use_id = users.use_id
        INNER JOIN persons ON users.use_id = persons.per_id WHERE $id = access.acc_id;"); 
        if ($access == null) {
            return response()->json([
               'status' => false,
                "data" => ['message' => 'The searched access was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Access por dato especifico : $id",4,$proj_id,$use_id);
            return response()->json([
              'status' => true,
                'data' => $access
            ]);
        }
    }

        public function update($proj_id,$use_id,Request $request,$id)
    {
        $acces = Access::find($id);
        $msg = $acces->acc_id;
        if($acces == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'The searched access was not found']
            ],400);
        }else{
            $rules = [
                'proj_id' =>'required|integer',
                'use_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                  'status' => False,
                 'message' => $validator->errors()->all()
                ]);
            }else{
                $acces->acc_status = $request->acc_status;
                $acces->proj_id = $request->proj_id;
                $acces->use_id = $request->use_id;
                $acces->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Access del dato: id->$msg",1,$proj_id,$use_id);
                return response()->json([
                 'status' => True,
                 'message' => "The access: ".$acces->use_id." has been updated."
                ],200);
            }
        }
    }
    public function destroy($proj_id,$use_id,$id)
    {
        $access = Access::find($id);
        $newStatus  = ($access->status == 1) ? 1 : 0;
        $access->use_status = $newStatus;
        $access->save();
        Controller::NewRegisterTrigger("Se le cambio el acceso al usuario:".$id.", por $newStatus ",2,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'message' => 'user status updated successfully'
        ]);
    }
}
