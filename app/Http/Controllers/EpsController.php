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
    public function index($proj_id)
    {
        $token = Controller::auth();
        try {
            $eps = Eps::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Eps",4,$proj_id, $token['use_id']);
            return response()->json([
                'status' => true,
                'data' => $eps
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
               'status' => false,
              'message' => "Error en index, not found elements"
            ],500);
        }
    }

    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();
        $rules = [
            'eps_name' => 'required|string|min:1|max:255|regex:/^[A-Z\s]+$/',
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla EPS : $request->eps_name ",3,$proj_id, $token['use_id']);
            return response()->json([
                'status' => True,
                'message' => "la eps ".$eps->eps_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($proj_id,$id)
    {
        $token = Controller::auth();
        $eps = Eps::find($id);
        if ($eps == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la eps solicitado']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla EPS por dato especifico: $id",4,$proj_id, $token['use_id']);
            return response()->json([
                'status' => true,
                'data' => $eps
            ]);
        }
    }

    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();
        $eps = Eps::find($id);
        if ($eps == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el genero solicitado']
            ],400);
        }else{
            $rules = [
                'eps_name' => 'required|string|min:1|max:255|regex:/^[A-Z\s]+$/',
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla EPS del dato: $id con el dato: $request->eps_name",1,$proj_id, $token['use_id']);
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
