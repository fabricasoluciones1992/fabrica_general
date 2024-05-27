<?php
namespace App\Http\Controllers;
use App\Models\Coformation_processes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CoformationProcessesController extends Controller
{
    public function index()
    {
        try{
            $coformation_process = Coformation_processes::SelectAll();
            if($coformation_process->isEmpty()) {
                return response()->json([
                    'status' => false,
                   'message' => 'No registers found.'
                ],400);
            }else{
                return response()->json([
                    'status' => true,
                    'data' => $coformation_process,
                ],200);
            }
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
            'cof_proc_observation' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
            'use_id' =>'required|integer|exists:users',
            'cof_pro_typ_id' => 'required|integer|exists:coformation_process_types',
            'peri_id' => 'required|integer|exists:periods', 
            'com_id' => 'required|integer|exists:companies',
            'stu_id' => 'required|integer|exists:students'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
            $coformation_process = new Coformation_processes($request->input());
            $coformation_process->cof_proc_status = 1;
            $coformation_process->save();
            Controller::NewRegisterTrigger("Se realizo una inserción en la tabla coformation processes",3,$request->use_id);
            return response()->json([
                'status' => true,
                'message' => "The process type '". $coformation_process->cof_proc_observation ."' has been added succesfully."
            ],200);
        }
    }
    public function show($coformation_process)
    {
        $coformation_process = Coformation_processes::ShowOne($coformation_process);
        if(!$coformation_process){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the coformation process you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $coformation_process,
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
            $rules = [
                'cof_proc_observation' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users',
                'cof_pro_typ_id' => 'required|integer|exists:coformation_process_types',
                'peri_id' => 'required|integer|exists:periods', 
                'com_id' => 'required|integer|exists:companies',
                'stu_id' => 'required|integer|exists:students'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {

                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $coformation_process = Coformation_processes::find($id);
                $coformation_process->cof_proc_observation = $request-> cof_proc_observation;
                $coformation_process->cof_pro_typ_id = $request-> cof_pro_typ_id;
                $coformation_process->peri_id = $request-> peri_id;
                $coformation_process->com_id = $request-> com_id;
                $coformation_process->stu_id = $request-> stu_id;
                $coformation_process->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla process type",1,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The process type with ID: ". $coformation_process->cof_proc_id." has been updated to '".$coformation_process->cof_proc_observation."' succesfully.",
                ],200);
            }
    }
    public function destroy($coformation_processes)
    {
        $coformation_process = Coformation_processes::find($coformation_processes);
        ($coformation_process->cof_proc_status == 1)? $coformation_process->cof_proc_status = 0 : $coformation_process->cof_proc_status = 1;
        $coformation_process->save(); 
        $message = ($coformation_process->cof_proc_status == 1)? 'Active' : 'Desactivated'; 
        Controller::NewRegisterTrigger("Se cambio el estado de un dato en la tabla coformation processes.",2,8);
        return response()->json([
            'status' => True,
            'message' => 'status changed: '.$message
        ],200);
    }
}