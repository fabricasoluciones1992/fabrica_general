<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diseases extends Model
{
    use HasFactory;
    protected $primaryKey = 'dis_id';
    protected $fillable = ['dis_disease'];
    public $timestamps = false;
}