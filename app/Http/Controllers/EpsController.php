<?php

namespace App\Http\Controllers;

use App\Models\Eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eps = Eps::all();
        return response()->json([
            'status' => true,
            'data' => $eps
        ],200);
    }

    public function store(Request $request)
    {
        $rules = [
            'eps_name' => 'required|string|min:1|max:255'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $eps = new Eps($request->input());
            $eps->save();
            return response()->json([
                'status' => True,
                'message' => "la eps ".$eps->eps_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($id)
    {
        $eps = Eps::find($id);
        if ($eps == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la eps solicitado']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $eps
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $eps = Eps::find($id);
        if ($eps == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el genero solicitado']
            ],400);
        }else{
            $rules = [
                'eps_name' => 'required|string|min:1|max:50'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $eps->eps_name = $request->eps_name;
                $eps->save();
                return response()->json([
                    'status' => True,
                    'message' => "la eps ".$eps->eps_name." ha sido actualizada exitosamente."
                ],200);
            }
        }
    }

    public function destroy(...$Eps)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
    }
}
