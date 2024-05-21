<?php

namespace App\Http\Controllers;

use App\Models\multiculturalisms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MulticulturalismsController extends Controller
{
    public function index()
    {
        try {
            $multiculturalism = multiculturalisms::all();
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
            'mul_name' => 'required|string|min:1|max:255|unique:multiculturalisms|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'use_id' =>'required|integer|exists:users'
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Multiculturalism : $request->mul_name ",3,6,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The multiculturalism: ".$multiculturalism->mul_name." has been created successfully."
            ],200);
        }
    }
    public function show($id)
    {
        $multiculturalism = multiculturalisms::find($id);
        if ($multiculturalism == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched culturalism was not found']
            ],400);
        }else{
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
                'mul_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->mul_name, 'multiculturalisms', 'mul_name', 'mul_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $multiculturalism->mul_name = $request->mul_name;
                $multiculturalism->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla multiculturalisms del dato: $id con el dato: $request->mul_name",1,6,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The multiculturalism: ".$multiculturalism->mul_name." has been updated successfully."
                ],200);
            }
        }
    }
    public function destroy(multiculturalisms $multiculturalisms)
    {
        return response()->json([ 
        'status' => false,
        'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
