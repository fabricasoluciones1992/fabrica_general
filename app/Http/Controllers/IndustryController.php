<?php
 
namespace App\Http\Controllers;
 
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class IndustryController extends Controller
{
    public function index()
    {
        try{
        $industries = Industry::all();
        return response()->json([
            'status' => true,
            'data' => $industries,
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
                    'ind_name' =>'required|string|unique:industries|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                    'use_id' =>'required|integer|exists:users'
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
                    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla industries",3,$request->use_id);
                    return response()->json([
                    'status' => true,
                    'message' => "The indrustry'". $industries->ind_name ."' has been added succesfully."
                ],200);}
    }
    public function show($industry)
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
    public function update(Request $request, $id)
    {
                $rules = [
                    'ind_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                    'use_id' =>'required|integer|exists:users'
                ];
                $validator = Validator::make($request->input(), $rules);
                $validate = Controller::validate_exists($request->ind_name, 'industries', 'ind_name', 'ind_id', $id);
                if ($validator->fails() || $validate == 0) {
                    $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                    return response()->json([
                    'status' => False,
                    'message' => $msg
                    ]);
                }else{
                    $industries = Industry::find($id);
                    $industries->ind_name = $request->ind_name;
                    $industries->save();
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla industries",1,$request->use_id);
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