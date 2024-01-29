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
        $multiculturalism = multiculturalisms::all();
        return response()->json([
          'status' => true,
            'data' => $multiculturalism
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
         'mul_name' =>'required|string|min:1|max:50'

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
            'mul_name' =>'required|string|min:1|max:50'

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
