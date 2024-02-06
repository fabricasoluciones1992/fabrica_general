<?php

namespace App\Http\Controllers;

use App\Models\multiculturalisms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MulticulturalismsController extends Controller
{
    public function index()
    {
        try {
            $multiculturalism = multiculturalisms::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Multiculturalism",4,6);
            return response()->json([
              'status' => true,
                'data' => $multiculturalism
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Error occurred while found elements"
            ],500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'mul_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

         'status' => False,
         'message' => $validator->errors()->all()
            ]);
        }else{
            $multiculturalism = new multiculturalisms($request->input());
            $multiculturalism->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Multiculturalism : $request->mul_name ",3,6);
            return response()->json([
         'status' => True,
         'message' => "El tipo de cultura ".$multiculturalism->mul_name." ha sido creado exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $multiculturalism = multiculturalisms::find($id);
        if ($multiculturalism == null) {
            return response()->json([
              'status' => false,
                "data" => ['message' => 'The searched culturalism was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Multiculturalism por dato especifico: $id",4,6);
            return response()->json([
              'status' => true,
                'data' => $multiculturalism
            ]);
        }
    }
    public function update(Request $request,$id)
    {
        $multiculturalism = multiculturalisms::find($id);
        if($multiculturalism == null) {
            return response()->json([
             'status' => false,
                'data' => ['message' => 'The searched culturalism was not found']
            ],400);
        }else{
            $rules = [
                'mul_name' => 'required|string|min:1|max:50|regex:/^[A-Z\s]+$/',

            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $multiculturalism->mul_name = $request->mul_name;
                $multiculturalism->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla multiculturalisms del dato: $id con el dato: $request->mul_name",1,6);
                return response()->json([
                'status' => True,
                'message' => "El tipo de cultura ".$multiculturalism->mul_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }
    public function destroy(multiculturalisms $multiculturalisms)
    {
        return response()->json([ 
        'status' => false,
        'message' => "Funcion no disponible"
        ],400);
    }
}
