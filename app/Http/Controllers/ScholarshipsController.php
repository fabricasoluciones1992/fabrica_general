<?php

namespace App\Http\Controllers;

use App\Models\scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ScholarshipsController extends Controller{
    public function index()
    {
        $scholarships = scholarships::all();
        return response()->json([
                'status' => true,
                'data' => $scholarships
            ], 200);
        
    }
    public function store( Request $request)
    {
        $rules = [

            'sch_name' => 'required|string|min:1|max:50|unique:scholarships|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'sch_description' => 'required|string|min:1|max:255|exists:scholarships|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $scholarship = new scholarships(($request->input()));
            $scholarship->save();
            Controller::NewRegisterTrigger("An insertion was made in the scholarships table'$scholarship->sch_id'", 3,$request->use_id);

            return response()->json([
                'status' => true,
                'message' => "The scholarship: " . $scholarship->sch_name . " has been created.",

            ], 200);
        }
    }

    public function show($id)
    {
        $scholarship = scholarships::find($id);

        if ($scholarship == null) {
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The scholarship requested not found'],
            ], 400);
        } else {

            return response()->json([
                'status' => true,
                'data' => $scholarship
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $scholarship = scholarships::find($id);
        if ($scholarship == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The scholarship requested not found.'],
            ], 400);
        } else {
            $rules = [

                'sch_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'sch_description' => 'required|string|min:1|max:255|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',

            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->sch_name, 'scholarships', 'sch_name', 'sch_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            } else {
                $scholarship = scholarships::find($id);
                $scholarship->sch_name = $request->sch_name;
                $scholarship->sch_description = $request->sch_description;

                $scholarship->save();
                Controller::NewRegisterTrigger("An update was made in the scholarships table: id->$id", 4, $request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The scholarship: " . $scholarship->sch_name . " has been update."
                ], 200);
            }
        }
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available."
        ], 400);
    }
}