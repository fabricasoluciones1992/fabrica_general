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
        $mail = mail::select();
        return response()->json([
            'status' => true,
            'data' => $mail
        ],200);    
    }
    public function store(Request $request)
    {
        $rules = [
            'mai_mail' => ['required','min:4','regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*\.[a-zA-Z]{2,}$/'],
            'mai_description' =>'string | max:255',
            'per_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ]);
        }else{
            $mail = new Mail($request->input());
            $mail->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla mails",3,6,$request->use_id);
            return response()->json([
              'status' => True,
              'message' => "The mail: ".$mail->mai_mail." has been added succesfully.",
              'data' => $mail->mai_id
            ],200);
        }
    }
    public function show($id)
    {
        $mail = mail::search($id);
        if ($mail == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find mail you are looking for']
            ],400);
        }else{
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
                'mai_mail' =>'required|string|max:255|regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*@[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9]+)*\.[a-zA-Z]{2,}$/',
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
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla mails",1,6,$request->use_id);
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
