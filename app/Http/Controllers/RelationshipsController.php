<?php

namespace App\Http\Controllers;

use App\Models\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class RelationshipsController extends Controller
{
    public function index()
    {
        $relationships = relationships::all();
        return response()->json([
            'status' => true,
            'data' => $relationships
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
         'rel_name' =>'required|string|min:1|max:50',
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
            return response()->json([
             'status' => True,
             'message' => "La relación ".$relationship->rel_name." ha sido creada exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $relationship = relationships::find($id);
        if ($relationship == null) {
            return response()->json([
             'status' => false,
                'data' => ['message' => 'no se encuentra la relación solicitada']
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
                'data' => ['message' => 'no se encuentra la relación solicitada']
            ]);
        }else{
            $rules = [
                'rel_name' =>'required|string|min:1|max:50',
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
