<?php

namespace App\Http\Controllers;

use App\Models\Genders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GendersController extends Controller
{
    public function index()
    {
        $genders = Genders::all();
        return response()->json([
            'status' => true,
            'data' => $genders
        ],200);
    }

    public function create()
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }

    public function store(Request $request)
    {
        $rules = [
            'gen_name' => 'required|string|min:1|max:50'
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
            return response()->json([
                'status' => True,
                'message' => "El genero ".$gender->gen_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($id)
    {
        $gender = Genders::find($id);
        if ($gender == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el genero solicitado']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $gender
            ]);
        }
    }

    public function edit()
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }

    public function update(Request $request, $id)
    {
        $gender = Genders::find($id);
        if ($gender == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el genero solicitado']
            ],400);
        }else{
            $rules = [
                'gen_name' => 'required|string|min:1|max:50'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $gender->gen_name = $request->gen_name;
                $gender->save();
                return response()->json([
                    'status' => True,
                    'message' => "El genero ".$gender->gen_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }

    public function destroy(Genders $genders)
    {
        return response()->json([
            'status' => true,
            'message' => "Funcion no disponible"
        ],400);
    }
}
