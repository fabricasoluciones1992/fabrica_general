<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class civilStates extends Model
{
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'civ_sta_id';

    protected $fillable = [
        'civ_sta_name',
    ];

    public $timestamps = false;

    use HasFactory;
}
