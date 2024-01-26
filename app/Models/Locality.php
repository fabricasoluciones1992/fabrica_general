<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'loc_id';

    protected $fillable = [
        'loc_name',
    ];

    public $timestamps = false;
}
