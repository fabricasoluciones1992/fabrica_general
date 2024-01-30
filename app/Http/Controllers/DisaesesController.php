<?php

namespace App\Http\Controllers;

use App\Models\Diseases;
use Illuminate\Http\Request;

class DiseasesController extends Controller
{
    public function index()
    {
        try {
            $disease = Diseases::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Diseases",4,6,1);
            return response()->json([
                'status' => true,
                'data' => $disease,
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
               'status' => false,
              'message' => $th
            ]); 
        }

    }
    public function store(Request $request)
    {
        $disease = new Diseases();
        $disease->dis_name = $request->dis_name;
        $disease->save();
        Controller::NewRegisterTrigger("Se creo un registro en la tabla Diseases: $request->dis_name",3,6,1);
        return response()->json([
            'status' => true,
            'data' => $disease,
        ],200);
    }
    public function show($id)
    {
        $disease = Diseases::find($id);
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla diseases por dato especifico: $id",4,6,1);
        return response()->json([
            'status' => true,
            'data' => $disease,
        ],200);
    }
    public function update(Request $request,$id)
    {
        $disease = Diseases::find($id);
        $disease->dis_name = $request->dis_name;
        $disease->save();
        Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Diseases del dato: id->$id->dis_id",1,6,1);
        return response()->json([
            'status' => true,
            'data' => $disease,
        ],200);
    }
    public function destroy(Diseases $diseases)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}