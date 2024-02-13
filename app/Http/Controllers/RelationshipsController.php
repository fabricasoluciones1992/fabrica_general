<?php

namespace App\Http\Controllers;

use App\Models\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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
            'rel_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑ\s]+$/',
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
             'message' => "La relación ".$relationship->rel_name." ha sido creada exitosamente."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
             'status' => false,
                'data' => ['message' => 'no se encuentra la relación solicitada']
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
                'data' => ['message' => 'no se encuentra la relación solicitada']
            ]);
        }else{
            $rules = [
                'rel_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $relationship->rel_name = $request->rel_name;
                $relationship->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Relationships del dato: $id con los datos: $request->rel_name ",1,$proj_id,$use_id);
                return response()->json([
                  'status' => True,
                  'message' => "La relación ".$relationship->rel_name." ha sido actualizada exitosamente."
                ],200);
            }
        }
    }
    public function destroy(relationships $relationships)
    {
        return response()->json([
          'status' => false,
          'message' => "Funcion no disponible"
        ],400);
    }
}
