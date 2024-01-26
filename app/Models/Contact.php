<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
