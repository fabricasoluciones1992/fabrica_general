<?php

namespace App\Http\Controllers;

use App\Models\Contract_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractTypeController extends Controller
{
    public function index()
    {
        try{
        $contract_Type = Contract_types::all();
        return response()->json([
            'status' => true,
            'data' => $contract_Type,
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
                'con_typ_name' =>'required|string|unique:contract_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $contract_Type = new Contract_types();
                $contract_Type->con_typ_name = $request->con_typ_name;
                $contract_Type->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla contract types",3,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The contract type '". $contract_Type->con_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($contract_Types)
    {
        $contract_Type = Contract_types::find($contract_Types);
        if(!$contract_Type){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the contract types you are looking for'],
            ],400);
        }else{
             return response()->json([
                'status' => true,
                'data' => $contract_Type,
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
            $rules = [
                'con_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->con_typ_name, 'contract_type', 'con_typ_name', 'con_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $contract_Type = Contract_types::find($id);
                $contract_Type->con_typ_name = $request->con_typ_name;
                $contract_Type->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla contract types",1,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The contract type with ID: ". $contract_Type -> con_typ_id." has been updated to '" . $contract_Type->con_typ_name ."' succesfully.",
                ],200);
            }
    }
    public function destroy(Contract_types $contract_Types)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}
