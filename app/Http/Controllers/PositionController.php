<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        return response()->json([
            'status' => true,
            'data' => $positions
        ],200);    
    }
    public function store(Request $request)
    {
        $rules = [
            'pos_name' =>'required|string|min:1|max:50',
            'are_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ]);
        }else{
            $position = new Position($request->input());
            $position->save();
            return response()->json([
              'status' => True,
              'message' => "La posición ".$position->pos_name." ha sido creada exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $position = Position::find($id);
        if ($position == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'no se encuentra la posición solicitada']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $position
            ]);
        }
    }
    public function update(Request $request, $id )
    {
        $positons = Position::find($id);
        if ($positons == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'no se encuentra la posición solicitada']
            ],400);
        }else{
            $rules = [
                'pos_name' =>'required|string|min:1|max:50',
                'are_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $positons->pos_name = $request->pos_name;
                $positons->are_id = $request->are_id;
                $positons->save();
                return response()->json([
                  'status' => True,
                  'message' => "La posición ".$positons->pos_name." ha sido actualizada exitosamente."
                ],200);
            }
        }
    }
    public function destroy(Position $position)
    {
        return response()->json([
           'status' => false,
           'message' => "Funcion no disponible"
        ],400);
    }
}
