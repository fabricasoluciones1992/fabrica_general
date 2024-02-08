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
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla genders",4,6);
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
            'gen_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla genders: $request->gen_name ",3,6);
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
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla genders por usuario especifico",4,6);
            return response()->json([
                'status' => true,
                'data' => $gender
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $gender = Genders::find($id);
        $msg = $gender->gen_name;
        if ($gender == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el genero solicitado']
            ],400);
        }else{
            $rules = [
                'gen_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla genders del dato: .$msg. con el dato: $request->gen_name",1,6);
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
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
    }
}
