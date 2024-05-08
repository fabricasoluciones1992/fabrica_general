<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_enrollments extends Model
{
    use HasFactory;
    protected $table = 'student_enrollments';
    protected $primaryKey = 'stu_enr_id';
    protected $fillable = [
        'stu_enr_semester',
        'stu_enr_status',
        'peri_id',
        'stu_id'
    ];
    public $timestamps = false;
}
