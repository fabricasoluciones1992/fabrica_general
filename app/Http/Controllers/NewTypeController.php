<?php

namespace App\Http\Controllers;

use App\Models\NewType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewTypeController extends Controller
{
    public function index()
    {

        $newtypes = NewType::all();
        return response()->json([
            'status' => true,
            'data' => $newtypes
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'new_typ_type' => 'required|string|min:1|max:50'

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $newtype = new NewType($request->input());
            $newtype->save();
            return response()->json([
             'status' => True,
             'message' => "El tipo de noticia ".$newtype->new_typ_type." ha sido creado exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $newType = NewType::find($id);
        if ($newType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el tipo de novedad solicitada']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $newType
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $newType = NewType::find($id);
        if ($newType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el tipo de novedad solicitada']
            ],400);
        }else{
            $rules = [
                'new_typ_type' => 'required|string|min:1|max:50'

            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
               'status' => False,
               'message' => $validator->errors()->all()
                ]);
            }else{
                $newType->new_typ_type = $request->new_typ_type;
                $newType->save();
                return response()->json([
             'status' => True,
                   'data' => "El tipo de noticia ".$newType->new_typ_type." ha sido actualizado exitosamente."
                ],200);
            };
        }
    }
    public function destroy(newType $newTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"

        ],400);
    }
}
