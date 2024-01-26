<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    use HasFactory;

    protected $primaryKey = 'eps_id';

    protected $fillable = [
        'eps_name',
    ];

    public $timestamps = false;
}
