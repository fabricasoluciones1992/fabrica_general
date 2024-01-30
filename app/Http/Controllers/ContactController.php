<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::all();
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla contact",4,6,1);
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

    public function store(Request $request)
    {
        $rules = [
            'con_name' => 'required|string|min:1|max:250',
            'con_relationship' => 'required|string|min:1|max:50',
            'con_mail' => 'required|string|email|min:1|max:250',
            'con_telephone' => 'required|numeric|min:10000|max:999999999999999'
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
            Controller::NewRegisterTrigger("Se creo un registro en la tabla Contact : $request->con_name, $request->con_relationship, $request->con_mail, $request->con_telephone ",3,6,1);
            return response()->json([
                'status' => True,
                'message' => "El contacto ".$contact->con_name." ha sido creado exitosamente."
            ],200);
        }
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el contacto solicitado']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla Contact por dato especifico: $id",4,6,1);
            return response()->json([
                'status' => true,
                'data' => $contact
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        if ($contact == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'no se encuentra el contacto solicitado']
            ],400);
        }else{
            $rules = [
                'con_name' => 'required|string|min:1|max:250',
                'con_relationship' => 'required|string|min:1|max:50',
                'con_mail' => 'required|string|email|min:1|max:250',
                'con_telephone' => 'required|numeric|min:10000|max:999999999999999'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $contact->con_name = $request->con_name;
                $contact->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla Contact del dato: $id con los datos: $request->con_name, $request->con_relationship, $request->con_mail, $request->con_telephone ",1,6,1);
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
        ],400);
    }
}
