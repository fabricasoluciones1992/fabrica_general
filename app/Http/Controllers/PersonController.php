<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PersonController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $persons = DB::select("SELECT * FROM ViewPersons");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla persons",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $persons
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }

    }
    public function store(Request $request)
    {
        //
    }
    public function show($proj_id,$use_id, $id)
    {
        try {
            $persons = DB::select("SELECT * FROM ViewPersons WHERE per_document = $id");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla persons",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $persons
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }

    public function profile($proj_id,$use_id)
    {
        try {
            $persons = DB::select("SELECT * FROM ViewPersons WHERE per_id = $use_id");
            Controller::NewRegisterTrigger("Se ingreso al perfil de: ".$persons[0]->per_name,4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $persons[0]
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }
    public function update($proj_id,$use_id,Request $request, $id)
    {
        $person = Person::find($id);
        if ($person == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The person requested is not found']
            ],400);
        }else{
            $rules = [
                'per_name'=> 'required|min:1|max:150|regex:/^[a-zA-ZñÑ\s]+$/',
                'per_lastname'=> 'required|min:1|max:100|regex:/^[a-zA-ZñÑ\s]+$/',
                'per_document'=> 'required|min:1000|max:999999999999999|integer',
                'per_expedition'=> 'required|date',
                'per_birthdate'=> 'required|date',
                'per_direction'=> 'required|min:1|max:255|regex:/^[a-zA-ZñÑ\s#\-]+$/u',
                'civ_sta_id'=> 'required|integer',
                'doc_typ_id'=> 'required|integer',
                'eps_id'=> 'required|integer',
                'gen_id'=> 'required|integer',
                'mul_id'=> 'required|integer',
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            }else{
                $person->per_name = $request->per_name;
                $person->per_lastname = $request->per_lastname;
                $person->per_document = $request->per_document;
                $person->per_expedition = $request->per_expedition;
                $person->per_birthdate = $request->per_birthdate;
                $person->per_direction = $request->per_direction;
                $person->civ_sta_id = $request->civ_sta_id;
                $person->doc_typ_id = $request->doc_typ_id;
                $person->eps_id = $request->eps_id;
                $person->gen_id = $request->gen_id;
                $person->mul_id = $request->mul_id;

                $person->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla persons del dato: $id con los datos: ",1,$proj_id,$use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The person: ".$person->per_name." has been update successfully."
                ],200);
            }
        }
    }

    public function update_password($proj_id,$use_id,Request $request)
    {
        $person = User::find($use_id);
        if ($request->use_password != $person->use_password) {
            return response()->json([
                'status' => False,
                'message' => "Password does not match"
            ],400);
        }
        $rules = [
            'new_password'=> 'required'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
            if ($request->new_password != $request->password_confirmation) {
                return response()->json([
                    'status' => False,
                    'message' => "Invalid password confirmation"
                ]);
            }
            $person->use_password = $request->new_password;
            $person->save();
            Controller::NewRegisterTrigger("se actualizo la contraseña del usuario: ".$person->use_mail,4,$proj_id,$use_id);
            return response()->json([
                'status' => True,
                'message' => "Password was successfully changed"
            ]);
        }
    }

    public function destroy($proj_id,$use_id,$id)
    {
        $user = User::find($id);
        $newStatus  = ($user->use_status == 1) ? 0 : 1;
        $user->use_status = $newStatus;
        $user->save();
        Controller::NewRegisterTrigger("Se cambio el estado de un dato en la tabla  ",4,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'message' => 'user status updated successfully'
        ]);
    }

    public function sendEmailReminder(Request $request)
    {
        $mail = new PHPMailer(true);
        $user = DB::table("users")->where('use_mail','=',$request->use_mail)->first();
        try {
    
            /* Email SMTP Settings */
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port = env('MAIL_PORT');
    
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->use_mail);
    
            $mail->isHTML(true);
    
            $mail->Subject = "Restauracion de Contraseña";
            $code = rand(100000,999999);
            DB::statement("INSERT INTO reset_passwords (`res_pas_code`, `use_id`) VALUES ($code, $user->use_id);");
            $mail->Body = "
            <!DOCTYPE html>
            <html lang='es'>
                <head>
                    <meta charset='utf-8'/>
                </head>
            <body>
            <p>Estimado(a) Usuario:</p>
            <p>Le confirmamos que ha cambiado de manera exitosa su contraseña. Puede dirigirse al siguiente enlace <a href='http://localhost:3000/resetPassword'>Restauracion de contraseña</a> e ingresar con su contraseña establecida</p>
            <p>Tu codigo de seguridad es: {$code}</p>
            <p>Este correo fue enviado automáticamente, agradecemos no responder este mensaje.</p>
            <p>Gracias por su atención.</p>
            </body>
            </html>";
            if( !$mail->send() ) {

                return response()->json([
                    'status' => True,
                    'message' => "Email not sent.".$mail->ErrorInfo
                ]);
            }
            else {
                return response()->json([
                    'status' => True,
                    'message' => "Email has been sent."
                ]);
            }
        } catch (Exception $e) {
                return $e;
        }
    }

    public function reset_password(Request $request)
    {
        $code = DB::select("SELECT * FROM reset_passwords WHERE res_pas_code = $request->res_pas_code");
        if ($code == null) {
            return response()->json([
                'status' => False,
                'message' => "Code does not match"
            ],400);
        }
        $rules = [
            'new_password'=> 'required'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
            if ($request->new_password != $request->password_confirmation) {
                return response()->json([
                    'status' => False,
                    'message' => "Invalid password confirmation"
                ],400);
            }
            $person = User::find($code[0]->use_id);
            $person->use_password = $request->new_password;
            $person->save();
            $useId = $code[0]->use_id;
            DB::select("DELETE FROM reset_passwords WHERE use_id = $useId");
            Controller::NewRegisterTrigger("se restauro la contraseña del usuario: ".$person->use_mail,4,6,$person->use_id);
            return response()->json([
                'status' => True,
                'message' => "Password was successfully changed"
            ]);
        }
    }

    public function filtredfortypeperson($proj_id,$use_id,Request $request)
    {
        $typPerson = Person::orderBy('per_id', 'desc')->paginate(10)->where('per_typ_id',$request->per_typ_id);
        return response()->json([
            'status' => true,
            'data' => $typPerson
        ],200);

    }

    public function viewForDocument($proj_id,$use_id,Request $request){
        $person = DB::select("SELECT ViewPersons.*, telephones.*, mails.*, contacts.*, medical_histories.*
        FROM ViewPersons
        INNER JOIN telephones ON ViewPersons.per_id = telephones.per_id
        INNER JOIN mails ON ViewPersons.per_id = mails.per_id
        INNER JOIN contacts ON ViewPersons.per_id = contacts.per_id
        INNER JOIN medical_histories ON ViewPersons.per_id = medical_histories.per_id
        WHERE per_document = $request->per_document AND doc_typ_id = $request->doc_typ_id");
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla persons",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $person
            ],200);

    }

}
