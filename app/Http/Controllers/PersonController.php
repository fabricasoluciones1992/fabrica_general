<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $person = Person::find($id);
        if ($person == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra la persona solicitada']
            ],400);
        }else{
            $rules = [
                'per_name'=> 'required|min:1|max:150|string',
                'per_lastname'=> 'required|min:1|max:100|string',
                'per_document'=> 'required|min:1000|max:999999999999999|integer',
                'per_expedition'=> 'required|date',
                'per_birthdate'=> 'required|date',
                'per_direction'=> 'required|min:1|max:255|string',
                'civ_sta_id'=> 'required|integer',
                'doc_typ_id'=> 'required|integer',
                'eps_id'=> 'required|integer',
                'gen_id'=> 'required|integer',
                'mul_id'=> 'required|integer',
                'use_id' => 'required|integer',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $person->per_name = $request->per_name;
                $person->per_lastname = $request->per_lastname;
                $person->per_document = $request->per_document;
                $person->per_expedition = $request->per_expedition;
                $person->per_birthdate = $request->per_birthdate;
                $person->per_direction = $request->per_direction;
                $person->civ_sta_id = $request->civ_sta_id;
                $person->doc_typ_id = $request->doc_typ_id;
                $person->eps_id = $request->eps_id;
                $person->gen_id = $request->gen_id;
                $person->mul_id = $request->mul_id;
                $person->use_id = $request->use_id;

                $person->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla persons del dato: $id con los datos: ",1,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => "la persona: ".$person->per_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }
    public function destroy(Person $person)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"

        ],400);
    }
}
