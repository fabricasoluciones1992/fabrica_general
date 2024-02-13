<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $persons = DB::select("SELECT * FROM ViewPersons");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla persons",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $persons
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
        //
    }
    public function show($proj_id,$use_id, $id)
    {
        try {
            $persons = DB::select("SELECT * FROM ViewPersons WHERE per_id = $id");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla persons",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $persons
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }
    public function update(Request $request, Person $person)
    {
        //
    }
    public function destroy(Person $person)
    {
        //
    }
}
