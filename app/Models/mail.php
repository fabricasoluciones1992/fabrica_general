<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class mail extends Model
{
    use HasFactory;
    protected $primaryKey = 'mai_id';
    protected $fillable = [
        'mai_mail',
        'mai_description',
        'per_id'
    ];
    public $timestamps = false;

    public static function select(){
        $mail = DB::select("SELECT mails.mai_id, mails.mai_mail, mails.mai_description, mails.per_id,persons.per_name,persons.per_lastname,persons.per_document,persons.doc_typ_id,document_types.doc_typ_name
        FROM mails
        INNER JOIN persons ON mails.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id");
        return $mail;
    }
    public static function search($id){
        $mail = DB::select("SELECT mails.mai_id, mails.mai_mail, mails.mai_description, mails.per_id,persons.per_name,persons.per_lastname,persons.per_document,persons.doc_typ_id,document_types.doc_typ_name
        FROM mails
        INNER JOIN persons ON mails.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id WHERE $id = mails.per_id");
        return $mail[0];
    }
}

