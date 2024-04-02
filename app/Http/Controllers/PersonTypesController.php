<?php

namespace App\Http\Controllers;

use App\Models\PersonTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonTypesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $personTypes = PersonTypes::all();
        return response()->json([
            'status' =>True,
            'data' => $personTypes
        ]);
    }
    public function create()
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
         ],400);
    }

    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'per_typ_name' => 'required|string|min:1|max:255|unique:person_types|regex:/^[A-ZÁÉÍÓÚÜÑ ]+$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $personType = new PersonTypes($request->input());
            $personType->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla person_types : $request->per_typ_name ",3,$proj_id,$use_id);
            return response()->json([
                'status' => True,
                'message' => "The person type:".$personType->per_typ_name."has been created successfully."
            ],200);
        }
    }

    public function show($proj_id,$use_id,$id)
    {
        $personType = PersonTypes::find($id);
        if ($personType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'not found person type']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $personType
            ]);
        }
    }

    public function edit($id)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
         ],400);
    }

    public function update($proj_id,$use_id,Request $request, $id)
    {
        $personType = PersonTypes::find($id);
        if ($personType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'not found person type']
            ],400);
        }else{
            $rules = [
                'per_typ_name' => 'required|string|min:1|max:255|regex:/^[A-ZÁÉÍÓÚÜÑ ]+$/'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->per_typ_name, 'person_types', 'per_typ_name', 'per_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $personType->per_typ_name = $request->per_typ_name;
                $personType->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla person$personType del dato: $id con el dato: $request->per_typ_name ",1,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'data' => "The personType:  ".$personType->per_typ_name." has been update successfully."
                ],200);
            };
        }
    }
    public function destroy(PersonTypes $personTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
         ],400);
    }
}
