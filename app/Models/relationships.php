<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relationships extends Model
{
    use HasFactory;

    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'rel_id';

    protected $fillable = [
        'rel_name',
    ];

    public $timestamps = false;
}
