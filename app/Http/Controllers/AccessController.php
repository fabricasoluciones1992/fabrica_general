<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AccessController extends Controller
{
    public function index()
    {
        $access = DB::select("SELECT access.acc_id, access.acc_status,projects.proj_name ,areas.are_name FROM access
        INNER JOIN projects ON access.proj_id = projects.proj_id
        INNER JOIN areas ON access.are_id = areas.are_id;");
        return response()->json([
          'status' => true,
            'data' => $access
        ],200);
    }

    public function store(Request $request)
    {
        $rules = [
            'acc_status' =>'required|string',
            'proj_id' =>'required|integer',
            'are_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
            $access = new Access($request->input());
            $access->save();
            return response()->json([
           'status' => True,
            'message' => "The access: ".$access->acc_status." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $access = DB::select("SELECT access.acc_id, access.acc_status,projects.proj_name ,areas.are_name FROM access
        INNER JOIN projects ON access.proj_id = projects.proj_id
        INNER JOIN areas ON access.are_id = areas.are_id WHERE $id = access.acc_id;"); 
        if ($access == null) {
            return response()->json([
               'status' => false,
                "data" => ['message' => 'The searched access was not found']
            ],400);
        }else{
            return response()->json([
              'status' => true,
                'data' => $access
            ]);
        }
    }

        public function update(Request $request,$id)
    {
        $acces = Access::find($id);
        if($acces == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'The searched access was not found']
            ],400);
        }else{
            $rules = [
                'acc_status' =>'required|string',
                'proj_id' =>'required|integer',
                'are_id' =>'required|integer'
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
                $acces->are_id = $request->are_id;
                $acces->save();
                return response()->json([
                 'status' => True,
                 'message' => "The access: ".$acces->acc_status." has been updated."
                ],200);
            }
        }
    }
    public function destroy(Access $access)
    {
        return response()->json([ 
           'status' => false,
           'message' => "Funcion no disponible"
         ],400);
    }
}
