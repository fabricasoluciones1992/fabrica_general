<?php

namespace App\Http\Controllers;

use App\Models\medicalhistories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MedicalhistoriesController extends Controller
{
    public function index($proj_id)
    {
        $token = Controller::auth();
        $medicalHistory = DB::select("SELECT mh.med_his_id, ds.dis_disease, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_mail
       FROM medical_histories mh
       INNER JOIN diseases ds ON ds.dis_id = mh.dis_id
       INNER JOIN persons p ON mh.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id
       ");
       Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla medical histories",4,$proj_id, $token['use_id']);
          return response()->json([
            'status' => true,
            'data' => $medicalHistory
        ],200);    
    }
    public function store($proj_id,Request $request)
    {
        $token = Controller::auth();
        $rules = [
             'per_id' =>'required|integer',
             'dis_id' =>'required|integer',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ],400);
        }else{
            $medicalHistory = new medicalhistories($request->input());
            $medicalHistory->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Medical Histories",3,$proj_id, $token['use_id']);
            return response()->json([
              'status' => True,
              'message' => "The medical history ". $medicalHistory -> per_name ." has been added succesfully."
            ],200);
        }
    }
    public function show($proj_id,$id)
    {
        $token = Controller::auth();
        $medicalHistory = DB::select("SELECT mh.med_his_id, ds.dis_disease, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_mail
       FROM medical_histories mh
       INNER JOIN diseases ds ON ds.dis_id = mh.dis_id
       INNER JOIN persons p ON mh.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id WHERE $id = mh.med_his_id");
        if ($medicalHistory == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the medical history you are looking for']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla Medical Histories",4,$proj_id, $token['use_id']);
            return response()->json([
               'status' => true,
                'data' => $medicalHistory
            ],200);
        }
    }
    public function update($proj_id,Request $request, $id)
    {
        $token = Controller::auth();
        $medicalHistory = medicalhistories::find($id);
        if ($medicalHistory == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required medical history']
            ],400);
        }else{
            $rules = [
                'per_id' =>'required|integer',
                'dis_id' =>'required|integer',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ],400);
            }else{
                $medicalHistory->per_id = $request->per_id;
                $medicalHistory->dis_id = $request->dis_id;
                $medicalHistory->save();
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla medical histories",1,$proj_id, $token['use_id']);
                return response()->json([
                  'status' => True,
                  'message' => "The medical history".$medicalHistory->med_his_id." has been updated succesfully."
                ],200);
            }
        }
    }
    public function destroy($proj_id)
    {
        $token = Controller::auth();
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla medical histories",2,$proj_id, $token['use_id']);
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
