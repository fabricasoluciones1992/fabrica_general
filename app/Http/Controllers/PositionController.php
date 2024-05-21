<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class PositionController extends Controller
{    
    public function index()
    {
        try {
            $positions = Position::select();
            return response()->json([
                'status' => true,
                'data' => $positions
            ],200);  
        } catch (\Throwable $th) {
            return response()->json([ 
               'status' => false,
              'message' => "Error occurred while found elements"
            ]);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'pos_name' => 'required|string|min:1|max:255|unique:positions|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'are_id' =>'required|integer|exists:areas',
            'use_id' =>'required|integer|exists:users'
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Position : $request->pos_name, $request->are_id ",3,6,$request->use_id);
            return response()->json([
              'status' => True,
              'message' => "The position: ".$position->pos_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $position = Position::search($id);
        if ($position == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'The position requested was not found']
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
                'data' => ['message' => 'The position requested was not found']
            ],400);
        }else{
            $rules = [
                'pos_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'are_id' =>'required|integer|exists:areas',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->pos_name, 'positions', 'pos_name', 'pos_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                  'status' => False,
                  'message' => $msg
                ]);
            }else{
                $positons->pos_name = $request->pos_name;
                $positons->are_id = $request->are_id;
                $positons->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla position del dato: $id con los datos: $request->pos_name, $request->are_id ",1,6,$request->use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The position: ".$positons->pos_name." has been update successfully."
                ],200);
            }
        }
    }
    public function destroy(Position $position)
    {
        return response()->json([
           'status' => false,
           'message' => "FUNCTION NOT AVAILABLE"
        ],400);
    }
}
