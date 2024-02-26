<?php

namespace App\Http\Controllers;

use App\Models\NewType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewTypeController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $newtypes = NewType::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla NewType",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $newtypes
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }

    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'new_typ_name' => 'required|string|min:1|max:50|unique:new_types|regex:/^[A-ZÁÉÍÓÚÜÑ]+$/'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $newtype = new NewType($request->input());
            $newtype->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla NewType : $request->new_typ_name ",3,$proj_id,$use_id);
            return response()->json([
             'status' => True,
             'message' => "The newType: ".$newtype->new_typ_name." has been created."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $newType = NewType::find($id);
        if ($newType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The NewType requested was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla NewType por dato especifico: $id",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $newType
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $newType = NewType::find($id);
        if ($newType == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The NewType requested was not found']
            ],400);
        }else{
            $rules = [
                'new_typ_name' => 'required|string|min:1|max:50|regex:/^[A-ZÁÉÍÓÚÜÑ]+$/'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->new_typ_name, 'new_types', 'new_typ_name', 'new_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
               'status' => False,
               'message' => $msg
                ]);
            }else{
                $newType->new_typ_name = $request->new_typ_name;
                $newType->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla NewType del dato: $id con el dato: $request->new_typ_name ",1,$proj_id,$use_id);
                return response()->json([
             'status' => True,
                   'data' => "The newType: ".$newType->new_typ_name." has been update successfully."
                ],200);
            };
        }
    }
    public function destroy(newType $newTypes)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"

        ],400);
    }
}
