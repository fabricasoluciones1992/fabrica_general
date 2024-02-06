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
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla DocTypes ",4,6);
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
    public function store(Request $request)
    {
        $rules = [
            'doc_typ_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla DocTypes: $request->doc_typ_name ",3,6);
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
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Doctypes por dato especifico: $id",4,6);
            return response()->json([
                'status' => true,
                'data' => $docTypes
            ]);
        }
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
                'doc_typ_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla DocTypes del dato: $id con el dato: $request->doc_typ_name",1,6);
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
