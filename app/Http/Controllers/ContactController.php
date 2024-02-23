<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ContactController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $contacts = DB::select("SELECT contacts.con_id, contacts.con_name, contacts.con_mail, contacts.con_telephone, relationships.rel_name, persons.per_name,contacts.rel_id,contacts.per_id
            FROM contacts
            INNER JOIN relationships ON contacts.rel_id = relationships.rel_id
            INNER JOIN persons ON contacts.per_id = persons.per_id");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla contact",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $contacts
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => $th
            ],500);
        }

    }

    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'con_name' => 'required|string|min:1|max:250|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
            'con_mail' => 'required|string|email|min:1|max:250|regex:/^[a-zñA-ZÑ]+[a-zñA-ZÑ._-]*@.*$/',
            'con_telephone' => 'required|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|min:7|max:15',
            'rel_id' => 'required|numeric|min:1|max:50',
            'per_id' => 'required|numeric|min:1|max:50',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $contact = new Contact($request->input());
            $contact->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Contact : $request->con_name, $request->con_relationship, $request->con_mail, $request->con_telephone ",3,$proj_id,$use_id);
            return response()->json([
                'status' => True,
                'message' => "El contacto ".$contact->con_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($proj_id,$use_id,$id)
    {
        $contacts = DB::select("SELECT contacts.con_id, contacts.con_name, contacts.con_mail, contacts.con_telephone, relationships.rel_name, persons.per_name,contacts.rel_id,contacts.per_id
            FROM contacts
            INNER JOIN relationships ON contacts.rel_id = relationships.rel_id
            INNER JOIN persons ON contacts.per_id = persons.per_id WHERE $id = contacts.con_id" );
        if ($contacts == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el contacto solicitado']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Contact por dato especifico: $id",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $contacts
            ]);
        }
    }

    public function update($proj_id,$use_id,Request $request, $id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el contacto solicitado']
            ],400);
        }else{
            $rules = [
                'con_name' => 'required|string|min:1|max:250|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'con_mail' => 'required|string|email|min:1|max:250|regex:/^[a-zñA-ZÑ]+[a-zñA-ZÑ._-]*@.*$/',
                'con_telephone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:7|max:15',
                'rel_id' => 'required|numeric|min:1|max:50',
                'per_id' => 'required|numeric|min:1|max:50'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $contact->con_name = $request->con_name;
                $contact->con_mail = $request->con_mail;
                $contact->con_telephone = $request->con_telephone;
                $contact->rel_id = $request->rel_id;
                $contact->per_id = $request->per_id;
                $contact->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Contact del dato: $id con los datos: $request->con_name, $request->con_relationship, $request->con_mail, $request->con_telephone ",1,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => "El contacto ".$contact->con_name." ha sido actualizado exitosamente."
                ],200);
            }
        }
    }

    public function destroy(contact $contacts)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
        ],400);FUNCTION NOT AVAILABLE
    }
}
