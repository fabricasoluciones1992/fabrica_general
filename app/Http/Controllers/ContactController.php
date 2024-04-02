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
            $contacts = Contact::select();
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
            'con_mail' =>'required|string|max:255|regex:/^[a-zA-Z0-9]+([-.]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([-.]?[a-zA-Z0-9]+)*\.[a-zA-Z]{2,}$/',
            'con_telephone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:7', 'max:15'],
            'rel_id' => 'required|integer',
            'per_id' => 'required|integer',
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
                'message' => "The contact: ".$contact->con_name." has been crated successfully.",
                'data' => $contact->con_id
            ],200);
        }
    }

    public function show($proj_id,$use_id,$id)
    {
        $contacts = Contact::search($id);
        if ($contacts == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'the contact requested not found']
            ],400);
        }else{
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
                'data' => ['message' => 'the contact requested not found']
            ],400);
        }else{
            $rules = [
                'con_name' => 'required|string|min:1|max:250|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/',
                'con_mail' => 'required|min:4|regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*\.[a-zA-Z]2,}$/',
                'con_telephone' => 'required|min:7|max:15|regex:/^[0-9\s\-\+\(\)]*$/',
                'rel_id' => 'required|integer',
                'per_id' => 'required|integer'
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
                    'message' => "The contact: ".$contact->con_name." has been updated successfully."
                ],200);
            }
        }
    }
    public function destroy(contact $contacts)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available"
        ],400);
    }
}
