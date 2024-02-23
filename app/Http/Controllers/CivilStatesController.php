<?php

namespace App\Http\Controllers;

use App\Models\civilStates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CivilStatesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $civilStates = civilStates::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla CivilStates",4,$proj_id,$use_id);
            return response()->json([
              'status' => true,
                'data' => $civilStates
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ]);
        }
    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'civ_sta_name' => 'required|string|min:1|max:50|unique:civil_states|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

          'status' => False,
          'message' => $validator->errors()->all()
            ]);
        }else{
            $civilStates = new civilStates($request->input());
            $civilStates->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla civilStates: $request->civ_sta_name ",3,$proj_id,$use_id );
            return response()->json([
          'status' => True,
          'message' => "The civil state: ".$civilStates->civ_sta_name." has been created."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $civilState = civilStates::find($id);
        if ($civilState == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'The requested civil state is not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla civilStates por dato especifico: $id",4,$proj_id,$use_id);
            return response()->json([
              'status' => true,
                'data' => $civilState
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request,$id)
    {
        $rules = [
            'civ_sta_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

          'status' => False,
          'message' => $validator->errors()->all()
            ]);
        }else{
            $civilState = civilStates::find($id);
            $msg = $civilState->civ_sta_name;
            if($civilState == null) {
            return response()->json([
                'status' => false,
                    'data' => ['message' => 'The requested civil state is not found']
                ],400);
            }else{
                $rules = [
                    'civ_sta_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                ];
                $validator = Validator::make($request->input(), $rules);
                $validate = Controller::validate_exists($request->civ_sta_name, 'civil_states', 'civ_sta_name', 'civ_sta_id', $id);
                if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                    return response()->json([
                'status' => False,
                'message' => $msg
                    ]);
                }else{
                    $civilState->civ_sta_name = $request->civ_sta_name;
                    $civilState->save();
                    Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla CivilStates del dato: $msg con el dato: $request->civ_sta_name",1,$proj_id,$use_id);
                    return response()->json([
                        'status' => True,
                        'message' => "The civil state: ".$civilState->civ_sta_name." has been update."
                    ],200);                
                }
            }
        }
    }
    public function destroy(civilStates $civilStates)
    {
        return response()->json([ 
       'status' => false,
       'message' => "Funcion no disponible"
        ],400);
    }
}
