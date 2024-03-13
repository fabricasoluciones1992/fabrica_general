<?php

namespace App\Http\Controllers;

use App\Models\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelationshipsController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $relationships = relationships::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Relationships",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $relationships
            ],200);
        } catch (\Throwable $th) {
            return response()->json([ 
                'status' => false,
                'message' => "Error occurred while found elements"
            ],500);
        }
    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'rel_name' => 'required|string|min:1|max:50|unique:relationships|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Relationships : $request->rel_name ",3,$proj_id,$use_id);
            return response()->json([
                'status' => True,
                'message' => "The relationship: ".$relationship->rel_name." has been created."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Relationship requested was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Relationships por dato especifico: $id",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $relationship
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Relationship requested was not found']
            ]);
        }else{
            $rules = [
                'rel_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Relationships del dato: $id con los datos: $request->rel_name ",1,$proj_id,$use_id);
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
          'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
