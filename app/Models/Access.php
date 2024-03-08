<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Access extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'acc_id';
    protected $table = 'access';
    protected $fillable = [
        'acc_status',
        'proj_id',
        'use_id'
    ];
    public $timestamps = false;
    public static function select(){
        $access = DB::select("SELECT access.acc_id,access.acc_status,projects.proj_name, access.use_id,users.use_mail,persons.per_id, persons.per_name,persons.per_document,projects.proj_id FROM access
        INNER JOIN projects ON access.proj_id = projects.proj_id
        INNER JOIN users ON access.use_id = users.use_id
        INNER JOIN persons ON users.use_id = persons.per_id");
        return $access;
    }
    public static function search($id){
        $access = DB::select("SELECT access.acc_id,access.acc_status,projects.proj_name, access.use_id,users.use_mail,persons.per_id, persons.per_name,persons.per_document,projects.proj_id FROM access
        INNER JOIN projects ON access.proj_id = projects.proj_id
        INNER JOIN users ON access.use_id = users.use_id
        INNER JOIN persons ON users.use_id = persons.per_id WHERE $id = access.acc_id;"); 
        return $access[0];
    }
}
