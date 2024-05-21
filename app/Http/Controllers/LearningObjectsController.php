<?php
namespace App\Http\Controllers;
use App\Models\Learning_Objects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class LearningObjectsController extends Controller
{
    public function index()
    {
        try{
        $learning_objects = Learning_Objects::all();
        return response()->json([
            'status' => true,
            'data' => $learning_objects,
        ],200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th
        ],500);
    }
    }
 
    public function store(Request $request)
    {
            $rules = [
                'lea_obj_object' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'lea_obj_subject' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'lea_obj_semester' =>'required|numeric|max:7|min:1',
                'cof_id'=>'required|integer|exists:conformacion'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $learning_objects = new Learning_Objects($request->input());
                $learning_objects->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla learning objects",3,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The learning objects '". $learning_objects->lea_obj_object ."' has been added succesfully."
                ],200);
            }
    }
    public function show($learning_Objects)
    {
        $learning_object = Learning_Objects::find($learning_Objects);
        if(!$learning_object){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the learning objects you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $learning_object,
            ],200);
        }
    }
    public function update(Request $request, $learning_Objects)
    {
            $rules = [
                'lea_obj_object' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'lea_obj_subject' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'lea_obj_semester' =>'required|numeric|max:7|min:1',
                'cof_id'=>'required|integer|exists:coformacion'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
            $learning_objects = Learning_Objects::find($learning_Objects);
            $learning_objects->lea_obj_object = $request->lea_obj_object;
            $learning_objects->lea_obj_subject = $request->lea_obj_subject;
            $learning_objects->lea_obj_semester = $request->lea_obj_semester;
            $learning_objects->cof_id = $request->cof_id;
            $learning_objects->save();
            $cof = DB::table('coformacion')->where('cof_id', $learning_objects->cof_id)->first();
            Controller::NewRegisterTrigger("Se realizo una edición en la tabla learning objects",1,6,$request->use_id);
            return response()->json([
                'status' => true,
                'data' => "The learning objects with ID: ". $learning_objects->lea_obj_id." has been updated to proccess coformation '" . $cof->cof_id ."' succesfully.",
            ],200);}
    }
}