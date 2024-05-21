<?php
 
namespace App\Http\Controllers;
 
use App\Models\History_career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 
class HistoryCarrerController extends Controller
{
    public function index()
    {
        try{
        $history_careers = History_career::select();
        return response()->json([
            'status' => true,
            'data' => $history_careers,
        ],200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th
        ],500);
    }
    }
    public static function store(Request $request)
    {
            $rules = [
                'car_id' =>'required|integer|exists:careers',
                'stu_id' =>'required|integer|exists:students',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
            $history_careers = new History_career();
            $history_careers->car_id = $request->car_id;
            $history_careers->stu_id = $request->stu_id;
            $history_careers->save();
            $career = History_career::search($history_careers->his_car_id);
            Controller::NewRegisterTrigger("Se realizo una inserción en la tabla history careers",3,6,$request->use_id);
            return response()->json([
                'status' => true,
                'message' => "The history career '".$career->per_name ."' '". $career->car_name ."' has been added succesfully."
            ],200);
        }
    }
    public function show($History_career)
    {
        $history_careers = History_career::search_career($History_career);
        if(!$history_careers){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the history careers you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $history_careers,
            ],200);
        }
    }

    public function update()
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }

    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}