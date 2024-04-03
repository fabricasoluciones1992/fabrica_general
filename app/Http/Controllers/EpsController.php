<?php

namespace App\Http\Controllers;

use App\Models\Eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class EpsController extends Controller
{
    public function index()
    {
        try {
            $eps = Eps::all();
            return response()->json([
                'status' => true,
                'data' => $eps
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
               'status' => false,
              'message' => "Error in index, not found elements"
            ],500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'eps_name' => 'required|string|min:1|unique:eps|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s.]+$/'
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla EPS : $request->eps_name ",3,6,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The eps: ".$eps->eps_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $eps = Eps::find($id);
        if ($eps == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The eps requested was not found.']
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
                'data' => ['message' => 'The eps requested was not found']
            ],400);
        }else{
            $rules = [
                'eps_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s.]+$/',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->eps_name, 'eps', 'eps_name', 'eps_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $eps->eps_name = $request->eps_name;
                $eps->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla EPS del dato: $id con el dato: $request->eps_name",1,6,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The eps: ".$eps->eps_name." has been update."
                ],200);
            }
        }
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
