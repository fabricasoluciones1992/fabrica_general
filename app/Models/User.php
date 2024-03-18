<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'use_id';
    protected $fillable = [
        'use_mail',
        'use_password',
        'use_photo',
        'use_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public $timestamps = false;

    public static function filtredfortypeperson($column,$data){
        if ($column == "use_status") {
            $user = User::orderBy($column, 'DESC')->where($column,$data)->take(50);
            $useIds = $user->pluck('use_id')->toArray();
            $personasVinculadas = Person::whereIn('use_id', $useIds)->take(50)->get();
            foreach ($personasVinculadas as $person) {
                $persons = DB::select(" SELECT * FROM ViewPersons WHERE per_id = $person->use_id");
            } 
            return $persons;  
        }elseif ($column == "per_typ_id"){
            $user = DB::select(" SELECT * FROM ViewPersons WHERE per_typ_id = $data");
            return $user;
        }else{
            return response()->json([
                'status' => False,
               'message' => "Invalid column"
             ],400);
        }
         
    }
}
