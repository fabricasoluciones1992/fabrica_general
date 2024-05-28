<?php

namespace App\Http\Controllers;

use App\Models\Coformation_process_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoformationProcessTypesController extends Controller
{
    public function index()
    {
        try {
            $coformation_process_types = Coformation_process_types::all();
            if ($coformation_process_types->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No registers found.'
                ], 400);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $coformation_process_types,
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'cof_pro_typ_name' => 'required|string|unique:coformation_process_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $coformation_process_types = new Coformation_process_types($request->input());
            $coformation_process_types->save();
            Controller::NewRegisterTrigger("Se realizo una inserción en la tabla coformation processes", 3, $request->use_id);
            return response()->json([
                'status' => true,
                'message' => "The process type '" . $coformation_process_types->cof_pro_typ_name . "' has been added succesfully."
            ], 200);
        }
    }
    public function show($coformation_process_types)
    {
        $coformation_process_types = Coformation_process_types::find($coformation_process_types);
        if (!$coformation_process_types) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'Could not find the coformation process type you are looking for'],
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $coformation_process_types,
            ], 200);
        }
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'cof_pro_typ_name' => 'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            'use_id' => 'required|integer|exists:users',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {

            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $coformation_process_types = Coformation_process_types::find($id);
            $coformation_process_types->cof_pro_typ_name = $request->cof_pro_typ_name;
            $coformation_process_types->save();
            $validate = Controller::validate_exists($request->cof_pro_typ_name, 'coformation_process_types', 'cof_pro_typ_name', 'cof_pro_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                        ]);
            }
            Controller::NewRegisterTrigger("Se realizo una edición en la tabla process type", 1, $request->use_id);
            return response()->json([
                'status' => true,
                'data' => "The coformation process type with ID: " . $coformation_process_types->cof_pro_typ_id . " has been updated to '" . $coformation_process_types->cof_pro_typ_name . "' succesfully.",
            ], 200);
        }
    }
    public function destroy()
    {
        return response()->json([
            'status' => True,
            'message' => 'Function not available.'
        ], 200);
    }
}
