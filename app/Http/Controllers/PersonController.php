<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class PersonController extends Controller
{
    public function index()
    {
        try {
            $persons = Person::select();
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
    public function show( $id)
    {
        try {
            $persons = Person::findByDocument($id);
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
            $persons = Person::findByper($use_id);
            Controller::NewRegisterTrigger("Se ingreso al perfil de: ".$persons->per_name,4,$proj_id,$use_id);
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
    public function update(Request $request, $id)
    {
        $person = Person::find($id);
        if ($person == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The person requested is not found']
            ],400);
        }else{
            $documents = DB::select("SELECT doc_typ_id, per_document, per_id FROM persons");
            foreach ($documents as $document) {
                if ($document->per_document == $request->per_document && $document->doc_typ_id == $request->doc_typ_id && $document->per_id != $id) {
                    return response()->json([
                        'status' => False,
                        'message' => "The document you are trying to register already exists"
                    ]);
                }
            }
            $rules = [
                'per_name'=> 'required|min:1|max:150|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/',
                'per_lastname'=> 'required|min:1|max:100|regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚüÜ\s]+$/',
                'per_document'=> 'required|min:1|max:999999999999999|regex:/^[a-zA-ZñÑ\s0-9]+$/',
                'per_expedition'=> 'required|date|after_or_equal:per_birthdate|before_or_equal:now',
                'per_birthdate'=> 'required|date|before_or_equal:now',
                'per_direction'=> 'required|min:1|max:255|regex:/^(?=.*[a-zA-Z0-9])[\w\s\-\#\.]+$/',
                'civ_sta_id'=> 'required|integer',
                'doc_typ_id'=> 'required|integer',
                'eps_id'=> 'required|integer',
                'gen_id'=> 'required|integer',
                'mul_id'=> 'required|integer',
                'per_rh' => 'required',
                'per_typ_id' => 'required|integer',
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
                $person->per_rh = $request->per_rh;
                $person->per_typ_id = $request->per_typ_id; 
                $person->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla persons del dato: $id con los datos: ",1,6,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The person: ".$person->per_name." has been update successfully."
                ],200);
            }
        }
    }
    public function update_password(Request $request)
    {
        $person = User::find($request->use_id);
        if ($request->use_password != $person->use_password) {
            return response()->json([
                'status' => False,
                'message' => "The old password does not match"
            ]);
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
            if ($request->new_password == $person->use_password) {
                return response()->json([
                   'status' => False,
                   'message' => "New password cannot be the same as the old password"
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
                Controller::NewRegisterTrigger("se actualizo la contraseña del usuario: ".$person->use_mail,4,6,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "Password was successfully changed"
                ]);
            }
        }
    }
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        $newStatus  = ($user->use_status == 1) ? 0 : 1;
        $user->use_status = $newStatus;
        $user->save();
        Controller::NewRegisterTrigger("Se cambio el estado de un dato en la tabla  ",4,6,$request->use_id);
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
            // $mail->SMTPDebug = 2;
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
            if(!$mail->send() ) {
                return response()->json([
                    'status' => True,
                    'message' => "Email not sent."
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
        $code = Person::reset_password($request);
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
            $person = User::find($code->use_id);
            $person->use_password = $request->new_password;
            $person->save();
            Person::deleteCode($code);
            Controller::NewRegisterTrigger("se restauro la contraseña del usuario: ".$person->use_mail,4,6,$person->use_id);
            return response()->json([
                'status' => True,
                'message' => "Password was successfully changed"
            ]);
        }
    }

    public function filtredfortypeperson($proj_id,$use_id,$column,$data)
    {
        if ($column == 'use_status') {
            $personasVinculadas = User::filtredfortypeperson($column,$data);
            return response()->json([
                'status' => true,
                'data' => $personasVinculadas
            ],200);    
        }elseif($column == "per_typ_id"){
            $user = User::filtredfortypeperson($column,$data);     
            return response()->json([
                'status' => true,
                'data' => $user
            ],200); 
        }else{
            return response()->json([
               'status' => False,
              'message' => "Invalid column"
            ],400);
        }
    }
    public function viewForDocument(Request $request){
        $person = Person::viewForDocument($request);
        return response()->json([
            'status' => true,
            'data' => $person
        ],200);
    }
    public function passwordEmergency(Request $request){
        $person = Person::PasswordEmergency($request);
        $person->use_password = $request->use_password;
        $person->save();
        return response()->json([
            'status' => true,
            'data' => "The emergency password has been changed successfully."
        ]);
    }
    public function lastPersons(){
        try {
            $person = Person::lastPersons();
            return response()->json([
               'status' => true,
                'data' => $person
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => False,
              'message' => $th
            ],400);
        }

    }

    public function updatePhoto(Request $request, $id){
        $user = User::find($id);
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('file');
        $image->move(public_path('images'), "$user->use_mail.jpg");

        $imageUrl = base64_encode(asset('images/'."$user->use_mail.jpg"));

        $user->use_photo = $imageUrl;
        $user->save();
        return response()->json([
            'status' => true,
            'message' => "Image updated successfully"
        ], 200);
    }


    public static function findByDocument($id, $docTypeId){
        $persons = DB::select("SELECT * FROM ViewPersons WHERE per_document = $id AND doc_typ_id = $docTypeId");
        return $persons;
    }

    public function filtredforDocument($id, $docTypeId)
{
    try {
        $persons = PersonController::findByDocument($id, $docTypeId);

        return response()->json([
            'status' => true,
            'data' => $persons
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => "Error occurred while found elements"
        ], 500);
    }
}
}
