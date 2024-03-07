<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'con_id';
    protected $fillable = [
        'con_name',
        'con_relationship',
        'con_mail',
        'con_telephone',
    ];
    public $timestamps = false;
    public static function select(){
        $contacts = DB::select("SELECT contacts.con_id, contacts.con_name, contacts.con_mail, contacts.con_telephone, relationships.rel_name,contacts.per_id, persons.per_name,persons.per_lastname,persons.per_document,persons.doc_typ_id,document_types.doc_typ_name,contacts.rel_id
        FROM contacts
        INNER JOIN relationships ON contacts.rel_id = relationships.rel_id
        INNER JOIN persons ON contacts.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id");
        return $contacts;
    }
    public static function search($id){
        $contacts = DB::select("SELECT contacts.con_id, contacts.con_name, contacts.con_mail, contacts.con_telephone, relationships.rel_name,contacts.per_id, persons.per_name,persons.per_lastname,persons.per_document,persons.doc_typ_id,document_types.doc_typ_name,contacts.rel_id
        FROM contacts
        INNER JOIN relationships ON contacts.rel_id = relationships.rel_id
        INNER JOIN persons ON contacts.per_id = persons.per_id
        INNER JOIN document_types ON persons.doc_typ_id = document_types.doc_typ_id WHERE $id = contacts.con_id" );
        return $contacts[0];
    }
}
