<?php
namespace App\Http\Controllers;
use App\Models\Process_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProcessTypesController extends Controller
{
    public function index()
    {
        $process_type = Process_Types::all();
        return response()->json([
            'status' => true,
            'data' => $process_type,
        ],200);
    }
    public function store(Request $request)
    {
            $rules = [
                'pro_typ_name' =>'required|string|unique:process_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $process_type = new Process_Types($request->input());
                $process_type->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla process type",3,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The process type '". $process_type->pro_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($process_Types)
    {
        $process_type = Process_Types::find($process_Types);
        if(!$process_type){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the processs types you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $process_type,
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
            $rules = [
                'pro_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->pro_typ_name, 'process_types', 'pro_typ_name', 'pro_typ_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $process_type = Process_Types::find($id);
                $process_type->pro_typ_name = $request-> pro_typ_name;
                $process_type->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla process type",1,6,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The process type with ID: ". $process_type->pro_typ_id." has been updated to '".$process_type->pro_typ_name."' succesfully.",
                ],200);
            }
    }
    public function destroy(Process_Types $process_Types)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ],400);
    }
}