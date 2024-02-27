<?php
 
namespace App\Http\Controllers;
 
use App\Models\Diseases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class DiseasesController extends Controller
{
    public function index($proj_id, $use_id)
    {
        try {
            $disease = Diseases::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Diseases", 4, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $disease,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Error in index, not found elements"
            ], 500);
        }
    }
    public function store($proj_id, $use_id, Request $request)
    {
        $rules = [
            'dis_name' => 'required|string|min:1|max:50|unique:diseases|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $disease = new Diseases(($request->input()));
            $disease->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Diseases: $request->dis_name", 3, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'message' => "The Diseases: " . $disease->dis_name . " has been created."
            ], 200);
        }
    }
    public function show($proj_id, $use_id, $id)
    {
        $disease = Diseases::find($id);
        if ($disease == null) {
            return response()->json([
                'status' => False,
                'data' => ['message' => 'The disease requested not found'],
            ], 400);
 
        } else {
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Doctypes por dato especifico: $id", 4, $proj_id, $use_id);
            return response()->json([
                'status' => true,
                'data' => $disease
            ]);
        }
    }
    public function update($proj_id, $use_id, Request $request, $id)
    {
        $disease = Diseases::find($id);
        if ($disease == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The disease requested not found'],
 
            ], 400);
        } else {
            $rules = [
                'dis_name' => 'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
 
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->dis_name, 'diseases', 'dis_name', 'dis_id', $id);
            if ($validator->fails() || $validate == 0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
 
                    'status' => False,
                    'message' => $msg
                ]);
            } else {
                $disease = Diseases::find($id);
                $disease->dis_name = $request->dis_name;
                $disease->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Diseases del dato: id->$id", 1, $proj_id, $use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The Diseases: " . $disease->dis_name . " has been update."
                ], 200);
            }
        }
    }
    public function destroy(Diseases $diseases)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ], 400);
    }
}