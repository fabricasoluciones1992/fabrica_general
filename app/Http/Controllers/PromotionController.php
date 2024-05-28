<?php
namespace App\Http\Controllers;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Validator;
class PromotionController extends Controller
{
    public function index()
    {
        try{
        $promotions = Promotion::all();
        return response()->json([
            'status' => true,
            'data' => $promotions,
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
        'pro_name' => 'required|numeric|max:9999',
        'pro_group' => 'required|string|max:1',
        'use_id' =>'required|integer|exists:users'
    ];

    $validator = Validator::make($request->input(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->all()
        ]);
    } else {
        $existingPromotion = DB::table('promotions')
                                ->where('pro_name', $request->pro_name)
                                ->where('pro_group', $request->pro_group)
                                ->exists();

        if ($existingPromotion) {
            return response()->json([
                'status' => false,
                'message' => 'A promotion with the same name and group already exists.'
            ]);
        } else {
            $promotions = new Promotion();
            $promotions->pro_name = $request->pro_name;
            $promotions->pro_group = $request->pro_group;
            $promotions->save();
            Controller::NewRegisterTrigger("An insertion was made into the promotions table", 3, $request->use_id);
            return response()->json([
                'status' => true,
                'message' => "The promotion '". $promotions->pro_name ."' in group '". $promotions->pro_group ."' has been added successfully."
            ], 200);
        }
    }
}


 
 
    public function show($promotion)
    {
        $promotions = Promotion::find($promotion);
        if(!$promotions){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the promotion you are looking for.'],
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
                    'use_id' =>'required|integer|exists:users'
                ];
                $validator = Validator::make($request->input(), $rules);
                $validate = Controller::validate_exists($request->pro_name, 'promotions', 'pro_name', 'pro_group', $promotion);

                if ($validator->fails()||$validate) {
                    $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();

                    return response()->json([
                    'status' => False,
                    'message' => $msg
                    ]);
                }else{
                    $promotions = Promotion::find($promotion);
                    $promotions->pro_name = $request->pro_name;
                    $promotions->pro_group = $request->pro_group;
                    $promotions->save();
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla promotions",1,$request->use_id);
                    return response()->json([
                        'status' => true,
                        'data' => "The promotion with ID: ". $promotions -> pro_id." has been updated to '" . $promotions->pro_name ."' succesfully.",
                    ],200);
                }
        }
 
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available."
         ],400);
    }
}