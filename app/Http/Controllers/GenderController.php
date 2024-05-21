<?php

namespace App\Http\Controllers;

use App\Models\Genders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class GenderController extends Controller
{
    public function index()
    {
        try {
            $genders = Genders::all();
            return response()->json([
                'status' => true,
                'data' => $genders
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
            'gen_name' => 'required|string|min:1|max:255|unique:genders|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $gender = new Genders($request->input());
            $gender->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla genders: $request->gen_name ",3,6,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The gender: ".$gender->gen_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $gender = Genders::find($id);
        if ($gender == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The gender requested was not found']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $gender
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $gender = Genders::find($id);
        $gender_old = $gender->gen_name;
        if ($gender == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The gender requested was not found']
            ],400);
        }else{
            $rules = [
                'gen_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->gen_name, 'genders', 'gen_name', 'gen_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $gender->gen_name = $request->gen_name;
                $gender->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla genders del dato: .$gender_old. con el dato: $request->gen_name",1,6,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The gender: ".$gender->gen_name." has been update."
                ],200);
            }
        }
    }

    public function destroy(Genders $genders)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
