<?php
namespace App\Http\Controllers;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json([
            'status' => true,
            'data' => $promotions,
        ],200);
    }
 
    public function store(Request $request)
    {
                $rules = [
                    'pro_name' =>'required|numeric|max:9999',
                    'pro_group' =>'required|string|max:1',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $promotions = new Promotion();
                    $promotions->pro_name = $request->pro_name;
                    $promotions->pro_group = $request->pro_group;
                    $promotions->save();
                    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla promotions",3,6,$request->use_id);
                    return response()->json([
                        'status' => true,
                        'message' => "The promotion '". $promotions->pro_name ."' has been added succesfully."
                    ],200);
                }
        }
 
 
    public function show($promotion)
    {
        $promotions = Promotion::find($promotion);
        if(!$promotions){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the promotions you are looking for'],
            ],400);
        }else{
 
            return response()->json([
                'status' => true,
                'data' => $promotions,
            ],200);
        }
    }
 
    public function update(Request $request, $promotion)
    {
                $rules = [
                    'pro_name' =>'required|numeric|max:9999',
                    'pro_group' =>'required|string',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $promotions = Promotion::find($promotion);
                    $promotions->pro_name = $request->pro_name;
                    $promotions->pro_group = $request->pro_group;
                    $promotions->save();
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla promotions",1,6,$request->use_id);
                    return response()->json([
                        'status' => true,
                        'data' => "The promotion with ID: ". $promotions -> pro_id." has been updated to '" . $promotions->pro_name ."' succesfully.",
                    ],200);
                }
        }
 
    public function destroy(Promotion $promotion)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}