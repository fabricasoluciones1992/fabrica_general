<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student_enrollments extends Model
{
    use HasFactory;
    protected $table = 'student_enrollments';
    protected $primaryKey = 'stu_enr_id';
    protected $fillable = [
        'stu_enr_semester',
        'stu_enr_status',
        'stu_enr_date',
        'peri_id',
        'stu_id',
        'car_id',
        'pro_id'
    ];
    public $timestamps = false;

    public static function select(){
        $data = DB::select("select `student_enrollments`.stu_enr_id, `student_enrollments`.`stu_enr_semester`,`student_enrollments`.`stu_enr_date`, `student_enrollments`.`stu_enr_status`, `periods`.`peri_name`, `periods`.`peri_start`, `periods`.`peri_end`, `persons`.`per_name` from `student_enrollments` inner join `periods` on `periods`.`peri_id` = `student_enrollments`.peri_id inner join `viewStudents` on `viewStudents`.`stu_id` = `student_enrollments`.`stu_id` inner join `persons` on `persons`.`per_id` = `viewStudents`.`per_id`");
        return $data;
    }

    public static function search($id){
        $data = DB::select("select `student_enrollments`.stu_enr_id, `student_enrollments`.`stu_enr_semester`,`student_enrollments`.`stu_enr_date`, `student_enrollments`.`stu_enr_status`, `periods`.`peri_name`, `periods`.`peri_start`, `periods`.`peri_end`, `persons`.`per_name` from `student_enrollments` inner join `periods` on `periods`.`peri_id` = `student_enrollments`.peri_id inner join `viewStudents` on `viewStudents`.`stu_id` = `student_enrollments`.`stu_id` inner join `persons` on `persons`.`per_id` = `viewStudents`.`per_id` where = `viewStudents`.`stu_id` = $id");
        return $data[0];
    }
}
