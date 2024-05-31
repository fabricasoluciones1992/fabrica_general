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

    public static function search($id)
    {
        $student = DB::table('viewEnrollments')->where('per_document', '=', $id)->first();
        if ($student == []) {
            return $student;
        }
        $students = Student::find($student->stu_id);
        $student->semester = $students->lastEnrollments();
        $student->use_photo = base64_decode($student->use_photo);
        return $student;
    }

    public function lastEnrollments()
    { //traiga
        $data = DB::table('viewEnrollments')
            ->where('stu_id', $this->stu_id)
            ->orderBy('stu_enr_id', 'desc')
            ->first();
        return $data;
    }


    public static function viewForDocumentStudent($request)
    {
        try {
            $student = DB::select("SELECT viewStudents.* FROM viewStudents
            WHERE viewStudents.per_document = ? AND viewStudents.doc_typ_id = ?", [$request->per_document, $request->doc_typ_id]);

            $history_scholarships = DB::select("SELECT history_scholarships.*, scholarships.sch_name FROM history_scholarships INNER JOIN  scholarships ON history_scholarships.sch_id = scholarships.sch_id WHERE stu_id = ?", [$student[0]->stu_id]);

            $student_enrollmentsOn = DB::select("SELECT student_enrollments.*, careers.car_name, CONCAT(promotions.pro_name, ' ', promotions.pro_group) AS pro_name_with_group
            FROM student_enrollments 
            INNER JOIN careers ON student_enrollments.car_id = careers.car_id 
            INNER JOIN promotions ON student_enrollments.pro_id = promotions.pro_id 
            WHERE student_enrollments.stu_id = ? AND student_enrollments.stu_enr_status = 1", [$student[0]->stu_id]);

            $student_enrollmentsOff = DB::select("SELECT student_enrollments.*, careers.car_name, CONCAT(promotions.pro_name, ' ', promotions.pro_group) AS pro_name_with_group
            FROM student_enrollments 
            INNER JOIN careers ON student_enrollments.car_id = careers.car_id 
            INNER JOIN promotions ON student_enrollments.pro_id = promotions.pro_id 
            WHERE student_enrollments.stu_id = ? AND student_enrollments.stu_enr_status = 0", [$student[0]->stu_id]);

            $student[0]->history_scholarships = $history_scholarships;
            $student[0]->student_enrollmentsOn = $student_enrollmentsOn;
            $student[0]->student_enrollmentsOff = $student_enrollmentsOff;
            return $student[0];
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Error occurred while found elements"
            ], 500);
        }
    }
}
