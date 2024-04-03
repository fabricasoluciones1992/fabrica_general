<?php
 
namespace App\Http\Controllers;
 
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class IndustryController extends Controller
{
    public function index($proj_id, $use_id)
    {
        $industries = Industry::all();
        return response()->json([
            'status' => true,
            'data' => $industries,
        ],200);
    }
    public function store(Request $request, $proj_id, $use_id)
    {
                $rules = [
                    'ind_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $industries = new Industry();
                    $industries->ind_name = $request->ind_name;
                    $industries->save();
                    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla industries",3,$proj_id,$use_id);
                    return response()->json([
                    'status' => true,
                    'message' => "The indrustry'". $industries->ind_name ."' has been added succesfully."
                ],200);}
    }
    public function show($proj_id, $use_id,$industry)
    {
        $industries = Industry::find($industry);
        if(!$industries){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the industries you are looking for'],
            ],400);
        }else{
             return response()->json([
                'status' => true,
                'data' => $industries,
            ],200);
        }
    }
    public function update(Request $request, $proj_id, $use_id, $industry)
    {
                $rules = [
                    'ind_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $industries = Industry::find($industry);
                    $industries->ind_name = $request->ind_name;
                    $industries->save();
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla industries",1,$proj_id,$use_id);
                    return response()->json([
                    'status' => true,
                    'data' => "The industry with ID: ". $industries -> ind_id." has been updated to '" . $industries->ind_name ."' succesfully.",
                ],200);}
        }
    public function destroy(Industry $industry)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}