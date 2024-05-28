<?php
namespace App\Http\Controllers;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PhaseController extends Controller
{
    public function index()
    {
        try{
        $phases = Phase::all();
        return response()->json([
            'status' => true,
            'data' => $phases,
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
                'pha_name' =>'required|exists:phases|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $phases = new Phase();
                $phases->pha_name=$request->pha_name;
                $phases->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla phases",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The phase '". $phases->pha_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($phase)
    {
        $phases = Phase::find($phase);
        if(!$phases){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the phases you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $phases,
            ],200);
        }
    }
    public function update(Request $request, $phase)
    {
 
            $rules = [
                'pha_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->pha_name, 'phases', 'pha_name','pha_id', $phase);

            if ($validator->fails()) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();

                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $phases = Phase::find($phase);
                $phases->pha_name = $request->pha_name;
                $phases->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla phases",1,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The phase with ID: ". $phases -> pha_id." has been updated to '" . $phases->pha_name ."' succesfully.",
                ],200);
            }
    }
    public function destroy(Phase $phase)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available."
         ],400);
    }
}