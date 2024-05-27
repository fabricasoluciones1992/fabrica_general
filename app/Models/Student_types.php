<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_types extends Model
{
    use HasFactory;

    protected $primaryKey = 'stu_typ_id';

    protected $table = "students_types";

    protected $fillable = [
        'stu_typ_name',
    ];

    public $timestamps = false;
}
