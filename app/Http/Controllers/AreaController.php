<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return response()->json([
            'status' => true,
            'data' => $areas
        ],200);
    }

    public function store(Request $request)
    {
        $rules = [
            'are_name' => 'required|string|min:1|max:50'
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
            return response()->json([
                'status' => True,
                'message' => "La area ".$area->are_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($id)
    {
        $area = Area::find($id);
        if ($area == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la area solicitada']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $area
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $area = Area::find($id);
        if ($area == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la area solicitada']
            ],400);
        }else{
            $rules = [
                'are_name' => 'required|string|min:1|max:50'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $area->are_name = $request->are_name;
                $area->save();
                return response()->json([
                    'status' => True,
                    'message' => "la area ".$area->are_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }

    public function destroy(Area $areas)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
    }
}
