<?php

namespace App\Http\Controllers;

use App\Models\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelationshipsController extends Controller
{
    public function index()
    {
        try {
            $relationships = relationships::all();
            return response()->json([
                'status' => true,
                'data' => $relationships
            ],200);
        } catch (\Throwable $th) {
            return response()->json([ 
                'status' => false,
                'message' => "Error occurred while found elements."
            ],500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'rel_name' => 'required|string|min:1|max:255|exists:relationships|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $relationship = new relationships($request->input());
            $relationship->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Relationships : $request->rel_name ",3,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The relationship: ".$relationship->rel_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Relationship requested was not found']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $relationship
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Relationship requested was not found']
            ]);
        }else{
            $rules = [
                'rel_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->rel_name, 'relationships', 'rel_name', 'rel_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                  'status' => False,
                  'message' => $msg
                ]);
            }else{
                $relationship->rel_name = $request->rel_name;
                $relationship->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Relationships del dato: $id con los datos: $request->rel_name ",1,$request->use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The Relationship ".$relationship->rel_name." has been update successfully."
                ],200);
            }
        }
    }
    public function destroy(relationships $relationships)
    {
        return response()->json([
          'status' => false,
          'message' => "FUNCTION NOT AVAILABLE."
        ],400);
    }
}
