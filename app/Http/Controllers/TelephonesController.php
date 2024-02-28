<?php

namespace App\Http\Controllers;

use App\Models\telephone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TelephonesController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $telephone = DB::select("SELECT telephones.tel_id, telephones.tel_number, telephones.tel_description, telephones.per_id, persons.per_name,persons.doc_typ_id, per_document
        FROM telephones
        INNER JOIN persons ON telephones.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id ");
        Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla telephones",4,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'data' => $telephone
        ],200);    
    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'tel_number' =>['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:7', 'max:15'],
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
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla telephones",3,$proj_id,$use_id );
            return response()->json([
              'status' => True,
              'message' => "The Telephone number ".$telephone->tel_number." has been added succesfully."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $telephone = DB::select("SELECT telephones.tel_id, telephones.tel_number, telephones.tel_description, telephones.per_id, persons.per_name,persons.doc_typ_id, per_document
        FROM telephones
        INNER JOIN persons ON telephones.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id  WHERE $id = telephones.per_id");
        if ($telephone == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the telephone number you are looking for']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla telephones",4,$proj_id,$use_id);
            return response()->json([
               'status' => true,
                'data' => $telephone
            ],200);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $telephone = Telephone::find($id);
        if ($telephone == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required telephone']
            ],400);
        }else{
            $rules = [
                'tel_number' =>['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:7', 'max:15'],
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
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla telephones",1,$proj_id,$use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The telephone ".$telephone->tel_number." has been updated succesfully."
                ],200);
            }
        }
        
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
