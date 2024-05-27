<?php

namespace App\Http\Controllers;

use App\Models\Covenant_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CovenantTypeController extends Controller
{
    public function index()
    {
        try{
        $covenant_type = Covenant_types::all();
        return response()->json([
            'status' => true,
            'data' => $covenant_type,
        ],200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th
        ],500);
    }
    }
    public function store(Request $request)
    {
            $rules = [
                'cov_typ_name' =>'required|string|unique:covenant_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $covenant_type = new Covenant_types();
                $covenant_type->cov_typ_name = $request->cov_typ_name;
                $covenant_type->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla covenant types",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The covenant type '". $covenant_type->cov_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($covenant_Types)
    {
        $covenant_type = Covenant_types::find($covenant_Types);
        if(!$covenant_type){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the covenant types you are looking for'],
            ],400);
        }else{
             return response()->json([
                'status' => true,
                'data' => $covenant_type,
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
            $rules = [
                'cov_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->cov_typ_name, 'covenant_types', 'cov_typ_name', 'cov_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $covenant_type = Covenant_types::find($id);
                $covenant_type->cov_typ_name = $request->cov_typ_name;
                $covenant_type->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla contract types",1,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The contract type with ID: ". $covenant_type -> cov_typ_id." has been updated to '" . $covenant_type->cov_typ_name ."' succesfully.",
                ],200);
            }
    }
    public function destroy(Covenant_types $covenant_Types)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available."
         ],400);
    }
}
