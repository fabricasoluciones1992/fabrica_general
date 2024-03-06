<?php

namespace App\Http\Controllers;

use App\Models\mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MailsController extends Controller
{
    public function index($proj_id,$use_id)
    {
        $mail = mail::select();
        Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla mails",4,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'data' => $mail
        ],200);    
    }
    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'mai_mail' => ['required','regex:/^[a-zñA-ZÑ]+[a-zñA-ZÑ._-]*@uniempresarial\.edu\.co$/'],
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
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla mails",3,$proj_id,$use_id);
            return response()->json([
              'status' => True,
              'message' => "The mail: ".$mail->mai_mail." has been added succesfully."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $mail = mail::find($id);
        if ($mail == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find mail you are looking for']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizó una busqueda en la tabla mails",4,$proj_id,$use_id);
            return response()->json([
               'status' => true,
                'data' => $mail
            ],200);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $mail = Mail::find($id);
        if ($mail == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required mail']
            ],400);
        }else{
            $rules = [
                'mai_mail' =>'required|string|max:255|regex:/^[a-zñA-ZÑ]+[a-zñA-ZÑ._-]*@.*$/',
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
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla mails",1,$proj_id,$use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The mail ".$mail->mai_mail." has been updated succesfully."
                ],200);
            }
        }
    }
    public function destroy(Mail $mail)
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
