<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects =DB::select("SELECT projects.proj_id, projects.proj_name, areas.are_name FROM projects INNER JOIN areas ON projects.are_id = areas.are_id;");
        return response()->json([
            'status' => true,
            'data' => $projects
        ],200);
    }

    public function store(Request $request)
    {
        $rules = [
            'proj_name' => 'required|string|min:1|max:50',
            'are_id' => 'required|numeric'
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
            return response()->json([
                'status' => True,
                'message' => "El proyecto ".$project->proj_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($id)
    {
        $project = DB::select("SELECT projects.proj_id, projects.proj_name, areas.are_name FROM projects INNER JOIN areas ON projects.are_id = areas.are_idWHERE projects.proj_id = $id;");
        if ($project == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el proyecto solicitada']
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
                'data' => ['message' => 'no se encuentra el proyecto solicitada']
            ],400);
        }else{
            $rules = [
                'proj_name' => 'required|string|min:1|max:50',
                'are_id' => 'required|numeric'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $project->proj_name = $request->proj_name;
                $project->are_id = $request->are_id;
                $project->save();
                return response()->json([
                    'status' => True,
                    'message' => "El proyecto ".$project->proj_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }

    public function destroy(project $projects)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);
    }
}
