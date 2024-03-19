<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'per_id';
    protected $table = "persons";
    protected $fillable = [
        'per_name',
        'per_lastname',
        'per_document',
        'per_expedition',
        'per_birthdate',
        'per_direction',
        'civ_sta_id',
        'doc_typ_id',
        'eps_id',
        'gen_id',
        'con_id',
        'mul_id',
        'use_id',
        'per_rh',
        'per_typ_id',
    ];
    public $timestamps = false;


    public static function select(){
        $persons = DB::select("SELECT * FROM ViewPersons");
        return $persons;
    }
    public static function findByDocument($id){
        $persons = DB::select("SELECT * FROM ViewPersons WHERE per_document = $id");
        return $persons;
    }
    public static function findByper($use_id){
        $persons = DB::select("SELECT * FROM ViewPersons WHERE per_id = $use_id");
        foreach ($persons as $person) {
            $mails = DB::table('mails')->where('per_id', '=', $person->per_id)->get();
            $telephones = DB::table('telephones')->where('per_id', '=', $person->per_id)->get();
            $contacts = DB::table('contacts')->where('per_id', '=', $person->per_id)->get();
            $medical_histories = DB::table('medical_histories')->where('per_id', '=', $person->per_id)->get(); 
            $person->mails = $mails;
            $person->telephones = $telephones;
            $person->contacts = $contacts;
            $person->medical_histories = $medical_histories;
        }
        return $persons[0];
    }
    public static function reset_password($request){
        $code = DB::select("SELECT * FROM reset_passwords WHERE res_pas_code = $request->res_pas_code");
        return $code[0];
    }
    public static function deleteCode($code){
        $useId = $code->use_id;
        DB::select("DELETE FROM reset_passwords WHERE use_id = $useId");
    }
    public static function viewForDocument($request){
        try {
            $person = DB::select("SELECT ViewPersons.* FROM ViewPersons
            WHERE ViewPersons.per_document = ? AND ViewPersons.doc_typ_id = ?", [$request->per_document, $request->doc_typ_id]);
            $mail = DB::table('mails')->where('per_id','=',$person[0]->per_id)->get();
            $telephones = DB::table('telephones')->where('per_id','=',$person[0]->per_id)->get();
            $contacts = DB::select("SELECT contacts.*, relationships.rel_name FROM contacts INNER JOIN relationships ON contacts.rel_id = relationships.rel_id WHERE per_id = ?", [$person[0]->per_id]);
            $medical_histories = DB::select("SELECT medical_histories.*, diseases.dis_name FROM medical_histories INNER JOIN diseases ON medical_histories.dis_id = diseases.dis_id WHERE per_id = ?", [$person[0]->per_id]);
            $person[0]->mails = $mail;
            $person[0]->telephones = $telephones;
            $person[0]->contacts = $contacts;
            $person[0]->medical_histories = $medical_histories;
            return $person[0];
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }

    }

    public static function PasswordEmergency($request){
        $person = DB::table('users')->where('use_mail','=',$request->use_mail)->first();
        $person = User::find($person->use_id);
        return $person;
    }
}
