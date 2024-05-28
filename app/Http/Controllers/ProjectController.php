<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $projects = Project::select();
            return response()->json([
                'status' => true,
                'data' => $projects
            ],200);
        } catch (\Throwable $th) {
            return response()->json([ 
             'status' => false,
             'message' => "Error occurred while found elements."
            ],500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'proj_name' => 'required|string|min:1|max:255|exists:projects|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'are_id' => 'required|integer|exists:areas',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $project = new project($request->input());
            $project->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Project : $request->proj_name, $request->are_id ",3,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The project: ".$project->proj_name." has been created."
            ],200);
        }
    }
    public function show($id)
    {
        $project = Project::search($id);
        if ($project == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Project requested was not found']
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $project
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if ($project == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The Project requested was not found']
            ],400);
        }else{
            $rules = [
                'proj_name' => 'required|string|min:1|max:255|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'are_id' => 'required|integer|exists:areas',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->proj_name, 'projects', 'proj_name', 'proj_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                    'status' => False,
                    'message' => $msg
                ]);
            }else{
                $project->proj_name = $request->proj_name;
                $project->are_id = $request->are_id;
                $project->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Project del dato: $id con los datos: $request->proj_name, $request->are_id ",4,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The project: ".$project->proj_name." has been update successfully."
                ],200);
            }
        }
    }
    public function destroy(project $projects)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE."
        ],400);
    }
}
