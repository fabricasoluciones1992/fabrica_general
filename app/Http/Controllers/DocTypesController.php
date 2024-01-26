<?php

namespace App\Http\Controllers;

use App\Models\DocumentTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocTypesController extends Controller
{
    public function index()
    {
        try {
            $doctypes = DocumentTypes::all();
            return response()->json([
              'status' => true,
                'data' => $doctypes
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error en index, not found elements"
            ],500);
        }

    }
    public function create()
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }
    public function store(Request $request)
    {
        $rules = [
            'doc_typ_name' =>'required|string|min:1|max:50'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
               'status' => False,
               'message' => $validator->errors()->all()
            ]);
        }else{
            $doctypes = new DocumentTypes($request->input());
            $doctypes->save();
            return response()->json([
               'status' => True,
               'message' => "El tipo de documento ".$doctypes->doc_typ_name." ha sido creado exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $docTypes = DocumentTypes::find($id);
        if($docTypes == null){
            return response()->json([
                'status' => False,
                'data' => ['message' => 'no se encuentra el tipo de documento solicitado'],

            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $docTypes
            ]);
        }
    }
    public function edit(DocumentTypes $DocumentTypes)
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }
    public function update(Request $request, $id)
    {
        $docTypes = DocumentTypes::find($id);
        if($docTypes == null){
            return response()->json([
               'status' => False,
                'data' => ['message' => 'no se encuentra el tipo de documento solicitado'],

            ],400);
        }else{
            $rules = [
                'doc_typ_name' =>'required|string|min:1|max:50'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $docTypes->doc_typ_name = $request->doc_typ_name;
                $docTypes->save();
                return response()->json([
                  'status' => True,
                  'message' => "El tipo de documento ".$docTypes->doc_typ_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }
    public function destroy(DocumentTypes $DocumentTypes)
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }
}
