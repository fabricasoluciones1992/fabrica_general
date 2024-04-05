<?php
namespace App\Http\Controllers;
use App\Models\Career_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CareerTypesController extends Controller
{
    public function index()
    {
        $career_types = Career_Types::all();
        return response()->json([
            'status' => true,
            'data' => $career_types,
        ],200);
    }
    public function store(Request $request)
    {
            $rules = [
                'car_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $career_types = new Career_Types();
                $career_types->car_typ_name = $request->car_typ_name;
                $career_types->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla career types",3,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The career type '". $career_types->car_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($career_Types)
    {
        $careers_types_types = Career_Types::find($career_Types);
        if(!$careers_types_types){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the career types you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $careers_types_types,
            ],200);
        }
    }
    public function update(Request $request,$career_Types)
    {
            $rules = [
                'car_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $careers_types = Career_Types::find($career_Types);
                $careers_types->car_typ_name = $request->car_typ_name;
                $careers_types->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla careers_types",1,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The career type with ID: ". $careers_types->car_typ_id." has been updated to '" . $careers_types->car_typ_name ."' succesfully.",
                ],200);
            }
    }
    public function destroy(Career_Types $career_Types)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}