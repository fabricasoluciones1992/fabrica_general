<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $areas = Area::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Area",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $areas
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ]);
        }

    }

    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'are_name' => 'required|string|min:1|unique:areas|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $area = new Area($request->input());
            $area->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Area: $request->are_name",3,$proj_id,$use_id);
            return response()->json([
                'status' => True,
                'message' => "The area: ".$area->are_name." has been created successfully."
            ],200);
        }
    }

    public function show($proj_id,$use_id,$id)
    {
        $area = Area::find($id);
        if ($area == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'the area requested was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Area por dato especifico : $id",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $area
            ]);
        }
    }

    public function update($proj_id,$use_id,Request $request, $id)
    {
        $area = Area::find($id);
        if ($area == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'the area requested was not found']
            ],400);
        }else{
            $rules = [
                'are_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->are_name, 'areas', 'are_name', 'are_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ],400);
            }else{
                $area->are_name = $request->are_name;
                $area->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Area del dato: id->$id",1,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The area: ".$area->are_name." has been update successfully."
                ],200);
            }
        }
    }

    public function destroy(Area $areas)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
