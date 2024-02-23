<?php

namespace App\Http\Controllers;

use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalityController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $localities = Locality::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Locality",4,$proj_id,$use_id);
            return response()->json([
              'status' => true,
                'data' => $localities
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
             'status' => false,
             'message' => $th
            ],500);
        }

    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'loc_name' => 'required|string|min:1|max:50|unique:localities|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
              'status' => false,
              'message' => $validator->errors()->all()
            ],400);
        }else{
            $localities = new Locality($request->input());
            $localities->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Locality : $request->loc_name ",3,$proj_id,$use_id);
            return response()->json([
            'status' => true,
            'message' => "The locality: ".$localities->loc_name." has been created."
            ],200);
        };
    }
    public function show($proj_id,$use_id,$id)
    {
        $localities = Locality::find($id);
        if($localities == null){
            return response()->json([
              'status' => false,
                'data' => ['message' => 'The Locality requested was not found']
                ],400);
            }else{
                Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla locality por dato especifico: $id",4,$proj_id,$use_id);
                return response()->json([
                'status' => true,
                   'data' => $localities
                ],200);
            }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $locality = Locality::find($id);
        if ($locality == null) {
             return response()->json([
                'status' => false,
                'data' => ['message' => 'The Locality requested was not found']
             ],400);
        }else{
            $rules = [
                'loc_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->loc_name, 'localities', 'loc_name', 'loc_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                 'status' => false,
                 'message' => $msg
                ],400);
            }else{
                $locality->loc_name = $request->loc_name;
                $locality->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Locality del dato: $id con el dato: $request->loc_name",1,$proj_id,$use_id);
                return response()->json([
               'status' => true,
                   'message' => "The localiti: .$locality->loc_name. has been updated successfully"
                ],200);
            };  
        }

    }
    public function destroy(Locality $locality)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
    }
}
