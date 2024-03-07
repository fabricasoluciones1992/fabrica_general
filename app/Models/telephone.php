<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class telephone extends Model
{
    use HasFactory;
    protected $primaryKey = 'tel_id';
    protected $fillable = [
        'tel_number',
        'tel_description',
        'per_id',
    ];
    public $timestamps = false;

    public static function select(){
        $telephone = DB::select("SELECT telephones.tel_id, telephones.tel_number, telephones.tel_description, telephones.per_id, persons.per_name,persons.per_lastname ,persons.doc_typ_id, per_document
        FROM telephones
        INNER JOIN persons ON telephones.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id ");
        return $telephone;
    }

    public static function search($id){
        $telephone = DB::select("SELECT telephones.tel_id, telephones.tel_number, telephones.tel_description, telephones.per_id, persons.per_name,persons.per_lastname,persons.doc_typ_id, per_document
        FROM telephones
        INNER JOIN persons ON telephones.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id  WHERE $id = telephones.per_id");
        return $telephone[0];
    }
}
