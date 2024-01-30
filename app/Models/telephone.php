<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class telephone extends Model
{
    use HasFactory;
    protected $primaryKey = 'tel_id';
    protected $fillable = [
        'tel_number',
        'tel_description',
        'per_id',
    ];
    public $timestamps = false;
}
