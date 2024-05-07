<?php
 
namespace App\Http\Controllers;
 
use App\Models\History_Promotion;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 
class HistoryPromotionController extends Controller
{
 
    public function index()
    {
        $history_promotions = History_Promotion::select();
        return response()->json([
            'status' => true,
            'data' => $history_promotions,
        ],200);
    }
    public function store(Request $request)
    {
                $rules = [
                    'pro_id' =>'required|integer',
                    'stu_id' =>'required|integer'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                $history_promotions = new History_Promotion();
                $history_promotions->pro_id = $request->pro_id;
                $history_promotions->stu_id = $request->stu_id;
                $history_promotions->save();
                $promotion = Promotion::search($history_promotions->pro_id);
                if(!$history_promotions){
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                        ]);
                }else{
                    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla history Promotions",3,6,$request->use_id);
                    return response()->json([
                        'status' => true,
                        'message' => "The history promotions '".$promotion->pro_name ."' of student '". $request->stu_id ."' has been added succesfully.",
                    ],200);}
                }
        }
    public function show($history_Promotion)
    {
        $history_promotions = History_Promotion::searchPromotions($history_Promotion);
        if(!$history_promotions){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the history promotions you are looking for'],
            ],400);
        }else{
 
            return response()->json([
                'status' => true,
                'data' =>$history_promotions
            ],200);
        }
    }
    public function update(Request $request, $history_Promotion)
    {
                $rules = [
                    'pro_id' =>'required|integer',
                    'stu_id' =>'required|integer'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                $history_promotions = History_Promotion::find($history_Promotion);
                $history_promotions->pro_id = $request->pro_id;
                $history_promotions->stu_id = $request->stu_id;
                $history_promotions->save();
                $promotion = History_Promotion::search($history_promotions);
                if(!$history_promotions){
                    return response()->json([
                        'status' => False,
                        'message' => $validator->errors()->all()
                        ]);
                }else{
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla history Promotions",1,6,$request->use_id);
                    return response()->json([
                        'status' => true,
                        'message' => "The history promotions '".$promotion->pro_name ."' of student '". $promotion->per_name ."' has been updated succesfully.",
                    ],200);}
                }
        }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}