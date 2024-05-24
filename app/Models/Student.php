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
        'stu_code',
        'stu_journey',
        'stu_rh',
        'stu_military',
        'stu_pier',
        'per_id',
        'loc_id',
        'mon_sta_id'
    ];
    public $timestamps = false;

    public static function search($id){
        $student = DB::table('viewStudents')->where('per_document','=', $id)->first();
        if ($student == []) {
           return $student;
        }
        $students = Student::find($student->stu_id);
        $student->promotion = $students->lastPromotion();
        $student->career = $students->lastCareer();
        $student->semester = $students->lastEnrollments();
        $student->use_photo = base64_decode($student->use_photo);
        $careers = DB::select("SELECT careers.car_name FROM history_careers INNER JOIN careers ON careers.car_id = history_careers.car_id WHERE stu_id = $students->stu_id");
        $promotions = DB::select("SELECT promotions.pro_name, promotions.pro_group FROM history_promotions INNER JOIN promotions ON promotions.pro_id = history_promotions.pro_id WHERE stu_id = $students->stu_id");
        $enrollments = DB::select("SELECT student_enrollments.stu_enr_semester, student_enrollments.stu_enr_status FROM students INNER JOIN student_enrollments ON student_enrollments.stu_id = students.stu_id WHERE students.stu_id = $students->stu_id");
        $student->careers = $careers;
        $student->promotions = $promotions;
        $student->enrollments = $enrollments;
        return $student;
    }


    public function lastPromotion(){
        $data = DB::table('history_promotions')
        ->join('promotions', 'history_promotions.pro_id', '=', 'promotions.pro_id')
        ->where('history_promotions.stu_id', $this->stu_id)
        ->orderBy('history_promotions.his_pro_id', 'desc')
        ->first();
        return $data->pro_name ."-". $data->pro_group;
    }

    public function lastCareer(){
        $data = DB::table('history_careers')
        ->join('careers', 'history_careers.car_id', '=', 'careers.car_id')
        ->where('history_careers.stu_id', $this->stu_id)
        ->orderBy('history_careers.his_car_id', 'desc')
        ->first();
        return $data;
    }

    public function lastEnrollments(){
        $data = DB::table('student_enrollments')
        ->where('stu_id', $this->stu_id)
        ->orderBy('stu_enr_id', 'desc')
        ->first();
        return $data->stu_enr_semester;
    }
}