<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $primaryKey = 'stu_id';
    protected $fillable = [
        'stu_stratum',
        'stu_journey',
        'stu_military',
        'stu_typ_id',
        'stu_piar',
        'per_id',
        'loc_id',
        'mon_sta_id'
    ];
    public $timestamps = false;

    public static function search($id){
        $student = DB::table('viewEnrollments')->where('per_document','=', $id)->first();
        if ($student == []) {
           return $student;
        }
        $students = Student::find($student->stu_id);
        $student->semester = $students->lastEnrollments();
        $student->use_photo = base64_decode($student->use_photo);
        return $student;
    }

    public function lastEnrollments(){//traiga
        $data = DB::table('viewEnrollments')
        ->where('stu_id', $this->stu_id)
        ->orderBy('stu_enr_id', 'desc')
        ->first();
        return $data;
    }
}