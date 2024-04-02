<?php

namespace App\Http\Controllers;
use App\Models\medicalhistories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class MedicalhistoriesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $medicalHistory = Medicalhistories::select();
          return response()->json([
            'status' => true,
            'data' => $medicalHistory
        ],200);    
    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
             'per_id' =>'required|integer',
             'dis_id' =>'required|integer',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ],400);
        }else{
            $medicalHistory = new medicalhistories($request->input());
            $medicalHistory->med_his_status = 1;
            $medicalHistory->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Medical Histories",3,$proj_id,$use_id);
            return response()->json([
              'status' => True,
              'message' => "The medical history ". $medicalHistory -> per_name ." has been added succesfully.",
              'data' => $medicalHistory->med_his_id
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $medicalHistory = medicalhistories::search($id);
        if ($medicalHistory == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the medical history you are looking for']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $medicalHistory
            ],200);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $medicalHistory = medicalhistories::find($id);
        if ($medicalHistory == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required medical history']
            ],400);
        }else{
            $rules = [
                'per_id' =>'required|integer',
                'dis_id' =>'required|integer',
                'med_his_status' =>'required|integer',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ],400);
            }else{
                $medicalHistory->per_id = $request->per_id;
                $medicalHistory->dis_id = $request->dis_id;
                $medicalHistory->med_his_status = $request->med_his_status;
                $medicalHistory->save();
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla medical histories",1,$proj_id,$use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The medical history: ".$medicalHistory->med_his_id." has been updated succesfully."
                ],200);
            }
        }
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
