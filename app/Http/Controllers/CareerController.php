<?php
namespace App\Http\Controllers;
use App\Models\Career;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CareerController extends Controller
{
    public function index()
    {
        $careers = Career::select();
        return response()->json([
            'status' => true,
            'data' => $careers,
        ],200);
    }
    public function store(Request $request)
    {
            $rules = [
                'car_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'car_typ_id'=>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $careers = new Career();
                $careers->car_name = $request->car_name;
                $careers->car_typ_id = $request->car_typ_id;
                $careers->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla careers",3,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The career '". $careers->car_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($career)
    {
        $careers = Career::search($career);
        if(!$careers){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the career you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $careers,
            ],200);
        }
    }
    public function update(Request $request, $career)
    {
            $rules = [
                'car_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'car_typ_id'=>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $careers = Career::find($career);
                $careers->car_name = $request->car_name;
                $careers->car_typ_id = $request->car_typ_id;
                $careers->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla careers",1,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The career with ID: ". $careers->car_id." has been updated to '" . $careers->car_name ."' succesfully.",
                ],200);
            }
    }
    public function destroy(Career $career)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}