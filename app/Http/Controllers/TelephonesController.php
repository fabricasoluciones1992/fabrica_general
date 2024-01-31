<?php

namespace App\Http\Controllers;

use App\Models\telephone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TelephonesController extends Controller
{
    public function index()
    {
        $telephone = DB::select("SELECT tl.tel_id, tl.tel_number, tl.tel_description, p.per_id, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_id, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_id
       FROM telephones tl
       INNER JOIN persons p ON tl.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id
       ");
        Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla telephones",4,6);
        return response()->json([
            'status' => true,
            'data' => $telephone
        ],200);    
    }
    public function store(Request $request)
    {
        $rules = [
            'tel_number' =>['required', 'regex:^(3)(0|1|2|3|5)[0-9]\d{7}$'],
            'tel_description' =>'string|max:255',
            'per_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ]);
        }else{
            $telephone = new telephone($request->input());
            $telephone->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla telephones",3,6);
            return response()->json([
              'status' => True,
              'message' => "The Telephone number ".$telephone->tel_number." has been added succesfully."
            ],200);
        }
    }
    public function show($id)
    {
        $telephone = DB::select("SELECT tl.tel_id, tl.tel_number, tl.tel_description, p.per_id, p.per_name, p.per_lastname, p.per_birthdate, 
        p.per_direction, cs.civ_sta_name, mc.muL_name, p.per_expedition, dt.doc_typ_id, dt.doc_typ_name, e.eps_name, g.gen_name, c.con_name, u.use_id
       FROM telephones tl
       INNER JOIN persons p ON tl.per_id = p.per_id
       INNER JOIN document_types dt ON dt.doc_typ_id = p.doc_typ_id
       INNER JOIN eps e ON e.eps_id = p.eps_id
       INNER JOIN genders g ON g.gen_id = p.gen_id
       INNER JOIN contacts c ON c.con_id = p.con_id
       INNER JOIN users u ON u.use_id = p.use_id
       INNER JOIN civil_states cs ON cs.civ_sta_id = p.civ_sta_id
       INNER JOIN multiculturalisms mc ON mc.mul_id = p.mul_id WHERE $id = tl.tel_id");
        if ($telephone == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the telephone number you are looking for']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla telephones",4,6);
            return response()->json([
               'status' => true,
                'data' => $telephone
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
        $telephone = Telephone::find($id);
        if ($telephone == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required telephone']
            ],400);
        }else{
            $rules = [
                'tel_number' =>'required|string|max:25',
                'tel_description' =>'string|max:255',
                'per_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $telephone->tel_number = $request->tel_number;
                $telephone->tel_description = $request->tel_description;
                $telephone->per_id = $request->per_id;
                $telephone->save();
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla telephones",1,6);
                return response()->json([
                  'status' => True,
                  'message' => "The telephone ".$telephone->tel_number." has been updated succesfully."
                ],200);
            }
        }
        
    }
    public function destroy()
    {
        Controller::NewRegisterTrigger("Se intentó eliminar un dato en la tabla telephones",2,6);
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
