<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class multiculturalisms extends Model
{
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'mul_id';

    protected $fillable = [
        'mul_name',
    ];

    public $timestamps = false;

    use HasFactory;
}
