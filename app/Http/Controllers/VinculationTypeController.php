<?php
namespace App\Http\Controllers;
use App\Models\Vinculation_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class VinculationTypeController extends Controller
{
    public function index()
    {
        try{
        $vinculation_types = Vinculation_Type::all();
        return response()->json([
            'status' => true,
            'data' => $vinculation_types,
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
                'vin_typ_name' =>'required|unique:vinculation_types|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $vinculation_type = new Vinculation_Type($request->input());
                $vinculation_type->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla vinculation type",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The vinculation type '". $vinculation_type->vin_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($vinculation_Type)
    {
        $vinculation_types = Vinculation_Type::find($vinculation_Type);
        if(!$vinculation_types){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the vinculation types you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $vinculation_types,
            ],200);
        }
    }
    public function update(Request $request, $vinculation_Type)
    {
            $rules = [
                'vin_typ_name' =>'required|string|unique:vinculation_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            // $validate = Controller::validate_exists($request->vin_typ_name, 'vinculation_types', 'vin_typ_name', 'car_typ_id', $vinculation_Type);

            if ($validator->fails()) {
                // $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();

                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $vinculation_type = Vinculation_Type::find($vinculation_Type);
                $vinculation_type->vin_typ_name = $request-> vin_typ_name;
                $vinculation_type->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla vinculation type",1,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The vinculation type with ID: ". $vinculation_type->vin_typ_id." has been updated to '".$vinculation_type->vin_typ_name."' succesfully.",
                ],200);
            }
    }
    public function destroy(Vinculation_Type $vinculation_Type)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ],400);
    }
}
