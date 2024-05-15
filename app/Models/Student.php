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
        'stu_scholarship',
        'stu_military',
        'per_id',
        'loc_id',
        'mon_sta_id'
    ];
    public $timestamps = false;

    public static function search($id){
        $student = DB::table('viewStudents')->where('per_document','=', $id)->first();
        $students = Student::find($student->stu_id);
        $student->promotion = $students->lastPromotion();
        $student->career = $students->lastCareer();
        $careers = DB::select("SELECT careers.car_name FROM history_careers INNER JOIN careers ON careers.car_id = history_careers.car_id WHERE stu_id = $students->stu_id");
        $promotions = DB::select("SELECT promotions.pro_name, promotions.pro_group FROM history_promotions INNER JOIN promotions ON promotions.pro_id = history_promotions.pro_id WHERE stu_id = $students->stu_id");
        $enrollments = DB::select("SELECT promotions.pro_name, promotions.pro_group FROM student_enrollments INNER JOIN promotions ON promotions.pro_id = student_enrollments.pro_id WHERE stu_id = $students->stu_id");
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
        return $data->car_name;
    }

    public function lastEnrollments(){
        $data = DB::table('student_enrollments')
        ->join('careers', 'history_careers.car_id', '=', 'careers.car_id')
        ->where('history_careers.stu_id', $this->stu_id)
        ->orderBy('history_careers.his_car_id', 'desc')
        ->first();
        return $data->car_name;
    }
}