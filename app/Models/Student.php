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
        $student->personal_contacts = $students->personalContacts();
        $student->emergency_contacts = $students->emergencyContacts();
        $student->medical_info = $students->medicalInfo();
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
    public function personalContacts()
    { //traiga
        $telephones = DB::table('students')
        ->select('telephones.*')
        ->join('persons as p1', 'p1.per_id', '=', 'students.per_id')
        ->join('telephones', 'telephones.per_id', '=', 'p1.per_id')
        ->where('students.stu_id', '=', $this->stu_id)
        ->get();
        $mails = DB::table('students')
        ->select('mails.*')
        ->join('persons as p1', 'p1.per_id', '=', 'students.per_id')
        ->join('mails', 'mails.per_id', '=', 'p1.per_id')
        ->where('students.stu_id', '=', $this->stu_id)
        ->get();
        $contacts = [
            'telephones' => $telephones,
            'mails' => $mails,
        ];
        return $contacts;
    }
    public function emergencyContacts()
    { //traiga
        $data = DB::table('students')
        ->select('contacts.*','relationships.rel_name')
        ->join('persons as p1', 'p1.per_id', '=', 'students.per_id')
        ->join('contacts', 'contacts.per_id', '=', 'p1.per_id')
        ->join('relationships', 'relationships.rel_id', '=', 'contacts.rel_id')
        ->where('students.stu_id', '=', $this->stu_id)
        ->get();
        return $data;
    }

    public function medicalInfo(){
        $diseases = DB::table('students')
        ->join('persons as p1', 'p1.per_id', '=', 'students.per_id')
        ->join('medical_histories', 'medical_histories.per_id', '=', 'p1.per_id')
        ->join('diseases', 'medical_histories.dis_id', '=', 'diseases.dis_id' )
        ->select('diseases.*')
        ->where('students.stu_id', '=', $this->stu_id)
        ->get();

        $allergies = DB::table('students')
        ->join('persons as p1', 'p1.per_id', '=', 'students.per_id')
        ->join('allergy_histories', 'allergy_histories.per_id', '=', 'p1.per_id',)
        ->join('allergies', 'allergy_histories.all_id', '=', 'allergies.all_id')
        ->select('allergies.*')
        ->where('students.stu_id', '=', $this->stu_id)
        ->get();
        $data = [
            'diseases' => $diseases,
            'allergies' => $allergies,
        ];
        return $data;
    }

    public static function viewForDocumentStudent($request)
    {
        try {
            $student = DB::select("SELECT viewStudents.* FROM viewStudents
            WHERE viewStudents.per_document = ? AND viewStudents.doc_typ_id = ?", [$request->per_document, $request->doc_typ_id]);
            if ($student == []) {
                return "the user not is a student";
            }
            $history_scholarships = DB::select("SELECT history_scholarships.*, scholarships.sch_name FROM history_scholarships INNER JOIN  scholarships ON history_scholarships.sch_id = scholarships.sch_id WHERE stu_id = ?", [$student[0]->stu_id]);

            $student_enrollmentsOn = DB::table('viewEnrollments')->select('pha_name','stu_id','stu_enr_status','peri_name','stu_enr_semester','stu_enr_journey','pro_name','car_name','stu_enr_date','car_name','car_id','peri_id','pha_id','peri_start','peri_end','car_typ_id','car_typ_name')->where('stu_enr_status', '=', 1)->where('stu_id','=',$student[0]->stu_id)->get();

            $student_enrollmentsOff = DB::table('viewEnrollments')->select('pha_name','stu_id','stu_enr_status','peri_name','stu_enr_semester','stu_enr_journey','pro_name','car_name','stu_enr_date','car_name','car_id','peri_id','pha_id','peri_start','peri_end','car_typ_id','car_typ_name')->where('stu_enr_status', '=', 0)->where('stu_id','=',$student[0]->stu_id)->get();

            $student[0]->use_photo = base64_decode($student[0]->use_photo);
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
