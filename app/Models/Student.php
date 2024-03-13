<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $primaryKey = 'stu_id';
    protected $fillable = [
        'stu_stratum',
        'stu_code',
        'stu_journey',
        'stu_rh',
        'stu_scholarship',
        'stu_military',
        'per_id',
        'loc_id',
        'mon_sta_id'
    ];
    public $timestamps = false;
}