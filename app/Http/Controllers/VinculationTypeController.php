<?php
namespace App\Http\Controllers;
use App\Models\Vinculation_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class VinculationTypeController extends Controller
{
    public function index()
    {
        $vinculation_types = Vinculation_Type::all();
        return response()->json([
            'status' => true,
            'data' => $vinculation_types,
        ],200);
    }
    public function store(Request $request)
    {
            $rules = [
                'vin_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
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
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla vinculation type",3,6,$request->use_id);
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
                'vin_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $vinculation_type = Vinculation_Type::find($vinculation_Type);
                $vinculation_type->vin_typ_name = $request-> vin_typ_name;
                $vinculation_type->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla vinculation type",1,6,$request->use_id);
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