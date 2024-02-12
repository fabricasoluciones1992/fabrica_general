<?php

namespace App\Http\Controllers;

use App\Models\mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MailsController extends Controller
{
    public function index()
    {
        $mail = DB::select("SELECT ml.mai_id, ml.mai_mail, ml.mai_description, p.per_id, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_id, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_id
       FROM mails ml
       INNER JOIN persons p ON ml.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id
       ");
       Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla mails",4,6);
          return response()->json([
            'status' => true,
            'data' => $mail
        ],200);    
    }
    public function store(Request $request)
    {
        $rules = [
            'mai_mail' => ['required','regex:^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'],
            'mai_description' =>'string | max:255',
            'per_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ],400);
        }else{
            $mail = new Mail($request->input());
            $mail->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla mails",3,6);
            return response()->json([
              'status' => True,
              'message' => "The mail ".$mail->mai_mail." has been added succesfully."
            ],200);
        }
    }
    public function show($id)
    {
        $mail = DB::select("SELECT ml.mai_id, ml.mai_mail, ml.mai_description, p.per_id, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_id, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_id
       FROM mails ml
       INNER JOIN persons p ON ml.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id WHERE $id = ml.mai_id");
        if ($mail == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find mail you are looking for']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla mails",4,5);
            return response()->json([
               'status' => true,
                'data' => $mail
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
        $mail = Mail::find($id);
        if ($mail == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required mail']
            ],400);
        }else{
            $rules = [
                'mai_mail' =>'required|string|max:255',
                'mai_description' =>'string|max:255',
                'per_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ],400);
            }else{
                $mail->mai_mail = $request->mai_mail;
                $mail->mai_description = $request->mai_description;
                $mail->per_id = $request->per_id;
                $mail->save();
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla mails",1,6);
                return response()->json([
                  'status' => True,
                  'message' => "The mail ".$mail->mai_mail." has been updated succesfully."
                ],200);
            }
        }
    }
    public function destroy(Mail $mail)
    {
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla mails",2,6);
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
