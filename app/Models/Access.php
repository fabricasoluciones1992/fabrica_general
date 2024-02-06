<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;


    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'acc_id';
    protected $table = 'access';

    protected $fillable = [
        'acc_status',
        'acc_adminitrator',
        'proj_id',
        'use_id'
    ];

    public $timestamps = false;
}
