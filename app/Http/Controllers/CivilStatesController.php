<?php

namespace App\Http\Controllers;

use App\Models\civilStates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CivilStatesController extends Controller
{
    public function index()
    {
        $civilStates = civilStates::all();
        return response()->json([
          'status' => true,
            'data' => $civilStates
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'civ_sta_name' =>'required|string|min:1|max:50'

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
            return response()->json([
          'status' => True,
          'message' => "El estado civil ".$civilStates->civ_sta_name." ha sido creado exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $civilState = civilStates::find($id);
        if ($civilState == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'no se encuentra el estado civil solicitada']
            ],400);
        }else{
            return response()->json([
              'status' => true,
                'data' => $civilState
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        $civilState = civilStates::find($id);
        if($civilState == null) {
        return response()->json([
              'status' => false,
                'data' => ['message' => 'no se encuentra el estado civil solicitada']
            ],400);
        }else{
            $rules = [
            'civ_sta_name' =>'required|string|min:1|max:50'

            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
               'status' => False,
               'message' => $validator->errors()->all()
                ]);
            }else{
                $civilState->civ_sta_name = $request->civ_sta_name;
                $civilState->save();
                return response()->json([
               'status' => True,
               'message' => "El estado civil ".$civilState->civ_sta_name." ha sido actualizado exitosamente."
                ],200);
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
