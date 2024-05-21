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
              'message' => "Error in index, not found elements"
            ],500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'doc_typ_name' => 'required|string|min:1|max:255|unique:document_types|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'use_id' =>'required|integer|exists:users'
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla DocTypes: $request->doc_typ_name ",3,6,$request->use_id);
            return response()->json([
               'status' => True,
               'message' => "The type document: ".$doctypes->doc_typ_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $docTypes = DocumentTypes::find($id);
        if($docTypes == null){
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The document typ requested not found'],
            ],400);
        }else{
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
                'data' => ['message' => 'The document typ requested not found'],
            ],400);
        }else{
            $rules = [
                'doc_typ_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->doc_typ_name, 'document_types', 'doc_typ_name', 'doc_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                  'status' => False,
                  'message' => $msg
                ]);
            }else{
                $docTypes->doc_typ_name = $request->doc_typ_name;
                $docTypes->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla DocTypes del dato: $id con el dato: $request->doc_typ_name",1,6,$request->use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The type document: ".$docTypes->doc_typ_name." has been update."
                ],200);
            }
        }
    }
    public function destroy(DocumentTypes $DocumentTypes)
    {
        return response()->json([
            'status' => true,
            'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
