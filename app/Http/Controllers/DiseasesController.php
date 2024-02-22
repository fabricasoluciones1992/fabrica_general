<?php

namespace App\Http\Controllers;

use App\Models\Diseases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiseasesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $disease = Diseases::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Diseases",4,$proj_id,$use_id);
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
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'dis_disease' => 'required|string|min:1|max:50|unique:disease|regex:/^[A-ZÑ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

          'status' => False,
          'message' => $validator->errors()->all()
            ]);
        }else{
            $disease = new Diseases();
            $disease->dis_disease = $request->dis_disease;
            $disease->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Diseases: $request->dis_name",3,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $disease,
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $disease = Diseases::find($id);
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla diseases por dato especifico: $id",4,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'data' => $disease,
        ],200);
    }
    public function update($proj_id,$use_id,Request $request,$id)
    {
        $rules = [
            'dis_disease' => 'required|string|min:1|max:50|unique:disease|regex:/^[A-ZÑ\s]+$/',

        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([

          'status' => False,
          'message' => $validator->errors()->all()
            ]);
        }else{
            $disease = Diseases::find($id);
            $disease->dis_disease = $request->dis_disease;
            $disease->save();
            Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Diseases del dato: id->$id->dis_id",1,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $disease,
            ],200);
        }
    }
    public function destroy(Diseases $diseases)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}