<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
