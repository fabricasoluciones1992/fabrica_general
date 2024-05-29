<?php
namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
{
    $lastStudents = DB::table('viewStudents')->get();
    $students = array();

    foreach ($lastStudents as $student) {
        $data = Student::find($student->stu_id);
        $lastEnrollment = $data->lastEnrollments();

        if (is_null($lastEnrollment)) {
            $studentData = $data;
            $studentData->Enrollment = "Missing Enrollment";
            array_push($students, $studentData);
        } else {
            array_push($students, $lastEnrollment);
        }
    }

    return response()->json([
        'status' => true,
        'data' => $students,
    ], 200);
}




    public function indexAmount()
    {
        $lastStudents = DB::table('viewStudents')->get();
        $students = array();
    
        foreach ($lastStudents as $student) {
            $data = Student::find($student->stu_id);
            $lastEnrollment = $data->lastEnrollments();
    
            if (is_null($lastEnrollment)) {
                $studentData = $data;
                $studentData->Enrollment = "Missing Enrollment";
                array_push($students, $studentData);
            } else {
                array_push($students, $lastEnrollment);
            }
        }
    
        return response()->json([
            'status' => true,
            'data' => $students,
        ], 200);
    }
    public function store(Request $request)
{
    $rules = [
        'stu_stratum' => 'required',
        'stu_journey' => 'required',
        'stu_military' => 'nullable|numeric|min:1|max:10',
        'stu_piar' => 'nullable|string|min:1|max:50|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',
        'stu_typ_id' => 'required|integer|exists:students_types',
        'per_id' => 'required|integer|exists:persons',
        'loc_id' => 'required|integer|exists:localities',
        'mon_sta_id' => 'required|integer|exists:monetary_states',
        'use_id'=>'required|integer|exists:users'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->all()
        ]);
    }

    $existingStudent = Student::where('per_id', $request->per_id)->first();
    if ($existingStudent) {
        return response()->json([
            'status' => false,
            'message' => "The student already exists"
        ]);
    }
    $request->merge([
        'stu_military' => $request->input('stu_military', 'N/A'),
        'stu_piar' => $request->input('stu_piar', 'N/A')
    ]);

    $student = new Student($request->all());
    $student->save();
    $stu_id = $student->stu_id;
    $request->merge(['stu_id' => $stu_id]);

    $person = DB::table('persons')->where('per_id', $student->per_id)->first();
    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla students", 3, $request->use_id);

    return response()->json([
        'status' => true,
        'stu_id' => $student->stu_id,
        'message' => "The student '". $person->per_name ."' has been added successfully."
    ], 200);
}


    public function show($student)
    {
        $students = Student::search($student);
        if(!$students){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the students you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $students,
            ],200);
        }
    }
    public function update(Request $request, $student)
    {
        $rules = [
        'stu_stratum' => 'required',
            'stu_journey' => 'required',
            'stu_military' => 'nullable|numeric|min:1|max:10',
            'stu_piar' => 'nullable|string|min:1|max:50|regex:/^[a-zA-Z0-9nÑÁÉÍÓÚÜáéíóúü\s\-,.;]+$/',
            'stu_typ_id' => 'required|integer|exists:students_types',
            'per_id' => 'required|integer|exists:persons',
            'loc_id' => 'required|integer|exists:localities',
            'mon_sta_id' => 'required|integer|exists:monetary_states',
        ];
        $validator = Validator::make($request->input(), $rules);
        $request->merge([
            'stu_military' => $request->input('stu_military', 'N/A'),
            'stu_piar' => $request->input('stu_piar', 'N/A')
        ]);
           if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
        $students = Student::find($student);
        $students->stu_stratum = $request->stu_stratum;
        $students->stu_typ_id = $request->stu_typ_id;
        $students->stu_journey = $request->stu_journey;
        $students->stu_piar = $request->stu_piar;
        $students->stu_military = $request->stu_military;
        $students->per_id = $request->per_id;
        $students->loc_id = $request->loc_id;
        $students->mon_sta_id = $request->mon_sta_id;
        $students->save();
        $person = DB::table('persons')->where('per_id','=',$students->per_id)->first();
        Controller::NewRegisterTrigger("Se realizo una edición en la tabla students",1,$request->use_id);
        return response()->json([
            'status' => true,
            'data' => "The student with ID: ". $students->stu_id." has been updated to '" . $person->per_name ."' succesfully.",
        ],200);}
        }
    public function destroy(Student $student)
    {
        return response()->json([
            'status' => false,
            'message' => "Function not available."
         ],400);
    }
}