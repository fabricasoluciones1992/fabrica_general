<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'new_id';

    protected $fillable = [
        'new_date',
        'new_type_id',
        'proj_id',
        'use_id',
    ];

    public $timestamps = false;
}
