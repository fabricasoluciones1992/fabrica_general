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
            $positions = DB::select("SELECT positions.pos_name, positions.pos_id, areas.are_name FROM positions INNER JOIN areas ON positions.are_id = areas.are_id;");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Position",4,6);
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Position : $request->pos_name, $request->are_id ",3,6);
            return response()->json([
              'status' => True,
              'message' => "La posición ".$position->pos_name." ha sido creada exitosamente."
            ],200);
        }
    }
    public function show($id)
    {
        $position = DB::select("SELECT positions.pos_name, positions.pos_id, areas.are_name FROM positions INNER JOIN areas ON positions.are_id = areas.are_id WHERE $id = positions.pos_id ;");
        if ($position == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'no se encuentra la posición solicitada']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Position por dato especifico: $id",4,6);
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla position del dato: $id con los datos: $request->pos_name, $request->are_id ",1,6);
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
