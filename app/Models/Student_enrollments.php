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
        $datas = DB::select("SELECT *
        FROM viewEnrollments");
        $enrollmentsType0 = [];
        $enrollmentsType1 = [];
        $enrollmentsType2 = [];

        foreach($datas as $data){
            $data->status_name = Student_enrollments::getStatusName($data->stu_enr_status);

            if($data->stu_enr_status==0){
                $enrollmentsType0[] = $data;
            }elseif($data->stu_enr_status==1){
                $enrollmentsType1[] = $data;

            }elseif($data->stu_enr_status==3){
                $enrollmentsType2[] = $data;

            }
        }
        return[ 
            'enrollment_Active'=>$enrollmentsType1,
            'enrollment_Inactive'=>$enrollmentsType0,
            'enrollment_Postpone'=>$enrollmentsType2,



        ];
    }
    public static function getStatusName($status) {
        switch ($status) {
            case 0:
                return 'Inactive';
            case 1:
                return 'Active';
            case 2:
                return 'Postpone'; 
        }
    }
    public static function inactive(){
        $data = DB::select("SELECT *
        FROM viewEnrollments
        WHERE stu_enr_status=0;
         ");
        return $data;
    }


    public static function search($id)
{
    $data = DB::select("SELECT *
        FROM viewEnrollments
        WHERE per_document = $id ");

    if (!empty($data)) {
        $enrollment = $data[0];

        if ($enrollment->stu_enr_status == 0) {
            return [
                'stu_enr_id' => $enrollment->stu_enr_id,
                'message' => 'This enrollment is inactive.'
            ];
        } else {
            return $enrollment;
        }
    } else {
        return null;
    }
}

    }

