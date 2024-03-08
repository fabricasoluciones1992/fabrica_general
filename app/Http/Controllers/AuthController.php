<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request, $proj_id){
        $rules = [
            'use_mail'=> 'required|min:1|max:250|email',
            'use_password'=> 'required|min:1|max:150|string'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ], 400);
        } else {
            $user = DB::table('users')->where('use_mail', '=', $request->use_mail)->first();
            if ($user->use_password == $request->use_password) {
                $user = User::find($user->use_id);
                $project_id = ($request->proj_id === null) ? env('APP_ID'): $request->proj_id;
                $access = DB::select("SELECT access.acc_status FROM access WHERE use_id = $user->use_id AND proj_id = $project_id");
                $acceso = ($access == null) ? 2 : $access[0]->acc_status;
                $use_status = $user->use_status;
                //Debe tener acceso si o si en el proyecto general
                if (($access == null && $proj_id == 6) || $acceso == 0 || $use_status == 0) {
                    return response()->json([
                        'status' => False,
                        'message' => "The user: ".$user->use_mail." has no access."
                       ],400);
                }
                    $acceso = ($acceso == 2) ? 0 : $acceso;
                    $tokens = DB::table('personal_access_tokens')->where('tokenable_id', '=', $user->use_id)->delete();
                    $project_id = ($request->proj_id === null) ? env('APP_ID') : $request->proj_id;
                    $person = Person::find($user->use_id);
                    Controller::NewRegisterTrigger("Se logeo un usuario: $user->use_mail", 4,$request->proj_id,$user->use_id);
                    return response()->json([
                        'status' => True,
                        'message' => "User login successfully",
                        'use_id' => $user->use_id,
                        'per_document' => $person->per_document,
                        'token' => $user->createToken('API TOKEN')->plainTextToken,
                        'acc_administrator' =>$acceso
                    ], 200);
            }else {
                return response()->json([
                    'status' => False,
                    'message' => "Invalid email or password"
                ], 401);
            }
        }
    }
    public function register($use_id,Request $request){
        $rules = [
            'use_mail'=> 'required|min:1|max:250|email|unique:users|regex:/^[a-zñA-ZÑ]+[a-zñA-ZÑ._-]*@uniempresarial\.edu\.co$/',
            'use_password'=> 'required|min:1|max:150|string',
            'per_name'=> 'required|min:1|max:150|string|regex:/^[a-zA-ZñÑ\s]+$/',
            'per_lastname'=> 'required|min:1|max:100|string|regex:/^[a-zA-ZñÑ\s]+$/',
            'per_document'=> 'required|min:1000|max:999999999999999|integer',
            'per_expedition'=> 'required|date',
            'per_birthdate'=> 'required|date',
            'per_direction'=> 'required|min:1|max:255|string',
            'per_rh' => 'required|min:1|max:3|string',
            'civ_sta_id'=> 'required|integer',
            'doc_typ_id'=> 'required|integer',
            'eps_id'=> 'required|integer',
            'gen_id'=> 'required|integer',
            'mul_id'=> 'required|integer',
            'per_typ_id'=> 'required|integer',
        ];

        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
            $documents = DB::select("SELECT doc_typ_id, per_document FROM persons");
            foreach ($documents as $document) {
                if ($document->per_document == $request->per_document && $document->doc_typ_id == $request->doc_typ_id) {
                    return response()->json([
                        'status' => False,
                        'message' => "The document you are trying to register already exists"
                    ]);
                }
            }
            $user = User::create([
                'use_mail' => $request->use_mail,
                'use_password' => $request->use_password,
                'use_status' => 1
            ]);
            $user->save();
            $person = Person::create([
                'per_name'=> $request->per_name,
                'per_lastname'=> $request->per_lastname,
                'per_document'=> $request->per_document,
                'per_expedition'=> $request->per_expedition,
                'per_birthdate'=> $request->per_birthdate,
                'per_direction'=> $request->per_direction,
                'per_rh' => $request->per_rh,
                'civ_sta_id'=> $request->civ_sta_id,
                'doc_typ_id'=> $request->doc_typ_id,
                'eps_id'=> $request->eps_id,
                'gen_id'=> $request->gen_id,
                'mul_id'=> $request->mul_id,
                'use_id'=> $user->use_id,
                'per_typ_id'=> $request->per_typ_id,
            ]);
            $person->save();
            Controller::NewRegisterTrigger("Se Registro un usuario: $request->per_name",3,6,$use_id);
            return response()->json([
                'status' => True,
                'message' => "User created successfully",
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ],200);
        }
    }

    public function logout(Request $id) {
        $tokens = DB::table('personal_access_tokens')->where('tokenable_id', '=', $id->use_id)->delete();
        return response()->json([
            'status'=> true,
            'message'=> "logout success."
        ]);
    }
}
