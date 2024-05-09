<?php
namespace App\Http\Controllers;
use App\Models\Student_enrollments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class StudentEnrollmentsController extends Controller
{
    public function index()
    {
        $students_enrollments = Student_enrollments::leftJoin('periods', 'periods.peri_id', '=', 'student_enrollments.peri_id')
        ->leftJoin('students', 'students.stu_id', '=', 'student_enrollments.stu_id')
        ->join('persons', 'persons.per_id', '=', 'students.per_id')
        ->select('student_enrollments.stu_enr_id', 'student_enrollments.stu_enr_semester', 'student_enrollments.stu_enr_status', 'periods.peri_name', 'periods.peri_start', 'periods.peri_end', 'persons.per_name')
        ->get();
        return response()->json([
            'status' => true,
            'data' => $students_enrollments,
        ],200);
    }
    public function store(Request $request)
    {

                $rules = [
                    'stu_enr_semester' =>'required|numeric|max:7|min:1',
                    'stu_id' =>'required',
                    'peri_id'=>'required'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                $students_enrollments = new Student_enrollments();
                $students_enrollments->stu_enr_semester = $request->stu_enr_semester;
                $students_enrollments->stu_enr_status = 1;
                $students_enrollments->stu_id = $request->stu_id;
                $students_enrollments->peri_id = $request->peri_id;
                $students_enrollments->save();
                $student = DB::table('viewStudents')->where('stu_id', $request->stu_id)->first();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla students_enrollments",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "the enrollment of student '".$student->per_name."' in semester '".$students_enrollments->stu_enr_semester."' the period '".$student->per_name."' has been added succesfully.",
                ],200);}
        }
    public function show($student_enrollments)
    {
        $students_enrollments = Student_enrollments::leftJoin('periods', 'periods.peri_id', '=', 'student_enrollments.peri_id')
        ->leftJoin('viewStudents', 'viewStudents.stu_id', '=', 'student_enrollments.stu_id')
        ->join('persons', 'persons.per_id', '=', 'viewStudents.per_id')
        ->select('student_enrollments.stu_enr_id', 'student_enrollments.stu_enr_semester', 'student_enrollments.stu_enr_status', 'periods.peri_name', 'periods.peri_start', 'periods.peri_end', 'persons.per_name')
        ->where('student_enrollments.stu_enr_id', '=', $student_enrollments)
        ->first();
        if(!$student_enrollments){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the student enrollments you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $students_enrollments,
            ],200);
        }
    }
    public function update(Request $request,$student_enrollments)
    {

                $rules = [
                    'stu_enr_semester' =>'required|numeric|max:7|min:1',
                    'stu_id' =>'required',
                    'peri_id'=>'required'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                $students_enrollments = Student_enrollments::find($student_enrollments);
                $students_enrollments->stu_enr_semester = $request->stu_enr_semester;
                $students_enrollments->stu_id = $request->stu_id;
                $students_enrollments->peri_id = $request->peri_id;
                $students_enrollments->save();
                $student = DB::table('viewStudents')->where('stu_id', $request->stu_id)->first();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla students enrollments",4,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "the enrollment of student '".$student->per_name."' in semester '".$students_enrollments->stu_enr_semester."' the period '".$student->per_name."' has been updated succesfully.",
                ],200);
            }
        }
    public function destroy(Request $request, $student_enrollments)
    {
            $students_enrollments = Student_enrollments::find($student_enrollments);
            $students_enrollments->stu_enr_status = $request->stu_enr_status;
            $students_enrollments->save();
            $statusMessage = ($students_enrollments->stu_enr_status == 1) ? "enabled" : "disenabled";
            Controller::NewRegisterTrigger("Se realizó una $statusMessage de datos en la tabla students enrollments", 2, $request->use_id);
            return response()->json([
                'status' => true,
                'message' => "the students enrollments has been $statusMessage"
            ], 200);

    }
}