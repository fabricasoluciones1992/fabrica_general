<?php
 
namespace App\Http\Controllers;
 
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        return response()->json([
            'status' => true,
            'data' => $activities,
        ],200);
 
    }
    public function store(Request $request)
    {
            $rules = [
                'acti_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $activities = new Activity();
                $activities->acti_name = $request->acti_name;
                $activities->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla activities",3,6,$request->use_id);;
                return response()->json([
                'status' => true,
                'message' => "The activity '". $activities->acti_name ."' has been added succesfully."
            ],200);}
    }
 
    public function show($activity)
    {
        $activities = Activity::find($activity);
        if(!$activities){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the activities you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $activities,
            ],200);
        }
    }
 
    public function update(Request $request, $activity)
    {
            $rules = [
                'acti_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $activities = Activity::find($activity);
                $activities->acti_name = $request->acti_name;
                $activities->save();
                        Controller::NewRegisterTrigger("Se realizo una edición en la tabla activities",1,6,$request->use_id);;
                return response()->json([
                'status' => true,
                'data' => "The activity with ID: ". $activities->acti_id." has been updated to '" . $activities->acti_name ."' succesfully.",
            ],200);}
    }
 
    public function destroy(Activity $activity)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}