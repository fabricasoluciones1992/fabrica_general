<?php

namespace App\Http\Controllers;

use App\Models\LearningObjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LearningObjectsController extends Controller
{

    public function index()
    {
        $learningObjects = LearningObjects::select();
        if($learningObjects == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
        }else{
            return response()->json([
                'status'=>True,
                'data'=>$learningObjects],200);
        }

    }




    public function store(Request $request)
    {


            $rules =[
                'lea_obj_object' => ['required'],
                'cor_mat_id' => 'required|integer|exists:core_material',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ],400);
            }else{
                $learningObject = new LearningObjects($request->input());
                $learningObject->lea_obj_object = $request->lea_obj_object;
                $learningObject->cor_mat_id = $request->cor_mat_id ;
                $learningObject ->save();
                Controller::NewRegisterTrigger("Se creo un registro en la tabla LearningObject: $request->lea_obj_object ",3,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => 'Learning object: '.$learningObject->lea_obj_object.' created successfully.',
                    'data' => $learningObject
                ],200);
            }
    }


    public function show($id)
    {
        $learningObjects = LearningObjects::findOne($id);
        if($learningObjects == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
        }else{
            return response()->json([
                'status'=>True,
                'data'=>$learningObjects],200);
        }
    }


    public function update(Request $request, $id)
    {
        $learningObject = LearningObjects::find($id);
        if($learningObject == null){
            return response()->json([
            'status' => False,
            'message' => 'There are no learning objects available.'
            ],400);
               }
         $rules =[
            'lea_obj_object' => ['required', 'regex:/^[A-ZÁÉÍÓÚÜÀÈÌÒÙÑ\s]+$/'],
            'cor_mat_id' => ['required']
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ],400);
        }else{
            $learningObject->lea_obj_object = $request->lea_obj_object;
            $learningObject->cor_mat_id = $request->cor_mat_id ;
            $learningObject ->save();
            Controller::NewRegisterTrigger("Se actualizó un registro en la tabla LearningObject: $request->lea_obj_object ",3,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => 'Learning object: '.$learningObject->lea_obj_object.' updated successfully.',
                'data' => $learningObject
            ],200);
        }
    }


    public function destroy(Request $request)
    {
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla CoreMaterial ",3,$request->use_id);
        return response()->json([
            'message' => 'This function is not allowed.'
        ],400);
    }
}
