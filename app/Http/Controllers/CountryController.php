<?php
namespace App\Http\Controllers;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CountryController extends Controller
{
    public function index()
    {
        try{
        $countries = Country::all();
        return response()->json([
            'status' => true,
            'data' => $countries,
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
                'cou_name' =>'required|string|unique:countries|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $countries = new Country();
                $countries->cou_name = $request->cou_name;
                $countries->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla countries",3,$request->use_id);
                return response()->json([
                'status' => true,
                'message' => "The country '". $countries->cou_name ."' has been added succesfully."
            ],200);}
    }
    public function show($country)
    {
        $countries = Country::find($country);
        if(!$countries){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the countries you are looking for'],
            ],400);
        }else{
             return response()->json([
                'status' => true,
                'data' => $countries,
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
            $rules = [
                'cou_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->cou_name, 'countries', 'cou_name', 'cou_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $countries = Country::find($id);
                $countries->cou_name = $request->cou_name;
                $countries->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla countries",1,$request->use_id);
                return response()->json([
                'status' => true,
                'data' => "The country with ID: ". $countries -> cou_id." has been updated to '" . $countries->cou_name ."' succesfully.",
            ],200);}
    }
    public function destroy(Country $country)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}