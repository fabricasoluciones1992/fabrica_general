<?php

namespace App\Http\Controllers;

use App\Models\Student_types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentTypeController extends Controller
{
    public function index()
    {
        try{
        $studentTypes = Student_types::all();
        return response()->json([
            'status' =>True,
            'data' => $studentTypes
        ]);
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
            'stu_typ_name' => 'required|string|min:1|max:255|unique:students_types|regex:/^[A-ZÁÉÍÓÚÜÑ ]+$/',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $studentType = new student_types($request->input());
            $studentType->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla student_types : $request->stu_typ_name ",3,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The student type: ".$studentType->stu_typ_name." has been created successfully."
            ],200);
        }
    }

    public function show($id)
    {
        $studentType = student_types::find($id);
        if ($studentType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'not found student type.']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $studentType
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $studentType = student_types::find($id);
        if ($studentType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'not found student type.']
            ],400);
        }else{
            $rules = [
                'stu_typ_name' => 'required|string|min:1|max:255|regex:/^[A-ZÁÉÍÓÚÜÑ ]+$/',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->stu_typ_name, 'students_types', 'stu_typ_name', 'stu_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $studentType->stu_typ_name = $request->stu_typ_name;
                $studentType->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla student $studentType del dato: $id con el dato: $request->stu_typ_name ",1,$request->use_id);
                return response()->json([
                    'status' => True,
                    'data' => "The student Type: ".$studentType->stu_typ_name." has been update successfully."
                ],200);
            };
        }
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE."
         ],400);
    }
}
