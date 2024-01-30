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
        try {
            $projects =DB::select("SELECT projects.proj_id, projects.proj_name, areas.are_name FROM projects INNER JOIN areas ON projects.are_id = areas.are_id;");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Project",4,6,1);
            return response()->json([
                'status' => true,
                'data' => $projects
            ],200);
        } catch (\Throwable $th) {
            return response()->json([ 
             'status' => false,
             'message' => "Error occurred while found elements"
            ],500);
        }

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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Project : $request->proj_name, $request->are_id ",3,6,1);
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
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Project por dato especifico: $id",4,6,1);
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
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Project del dato: $id con los datos: $request->proj_name, $request->are_id ",1,6,1);
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
