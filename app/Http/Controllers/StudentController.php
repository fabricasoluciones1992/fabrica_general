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
        try{
        $students = DB::table('viewStudents')->get();
        foreach ($students as $student) {
            $data = Student::find($student->stu_id);
            $student->promotion = $data->lastPromotion();
            $student->car_id = $data->lastCareer()->car_id;
            $student->career = $data->lastCareer()->car_name;
            $student->semester = $data->lastEnrollments();
        }
        return response()->json([
            'status' => true,
            'data' => $students,
        ],200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th
        ],500);
    }
    }

    public function indexAmount()
    {
        $students = DB::table('viewStudents')->orderBy('stu_id', 'desc')->take(50)->get();
        foreach ($students as $student) {
            $data = Student::find($student->stu_id);
            $student->promotion = $data->lastPromotion();
            $student->car_id = $data->lastCareer()->car_id;
            $student->career = $data->lastCareer()->car_name;
            $student->semester = $data->lastEnrollments();
        }
        return response()->json([
            'status' => true,
            'data' => $students,
        ],200);
    }
    public function store(Request $request)
    {
        $rules = [
            'stu_stratum' => 'required',
            'stu_journey' => 'required',
            'per_id' => 'required|integer|exists:persons',
            'loc_id' => 'required|integer|exists:localities',
            'mon_sta_id' => 'required|integer|exists:monetary_states',
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

        $student = new Student($request->all());
        $student->save();
        $stu_id = $student->stu_id;
        $request->merge(['stu_id' => $stu_id]);

        $promotionResponse = app('App\Http\Controllers\HistoryPromotionController')->store($request);
        $promotion = json_decode($promotionResponse->getContent(), true);
        if ($promotion['status'] === false) {
            $student->delete();
            return response()->json($promotion);
        }
    
        $careerResponse = app('App\Http\Controllers\HistoryCarrerController')->store($request);
        $career = json_decode($careerResponse->getContent(), true);
        if ($career['status'] === false) {
            $student->delete();
            return response()->json($career);
        }
    
        $enrollmentResponse = app('App\Http\Controllers\StudentEnrollmentsController')->store($request);
        $enrollment = json_decode($enrollmentResponse->getContent(), true);
        if ($enrollment['status'] === false) {
            $student->delete();
            return response()->json($enrollment);
        }
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
        'stu_scholarship' => 'required',
        'per_id' => 'required|integer|exists:persons',
        'loc_id' => 'required|integer|exists:localities',
        'mon_sta_id' => 'required|integer|exists:monetary_states',
        ];
        $validator = Validator::make($request->input(), $rules);
           if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        }else{
        $students = Student::find($student);
        $students->stu_stratum = $request->stu_stratum;
        $students->stu_code = $request->stu_code;
        $students->stu_journey = $request->stu_journey;
        $students->stu_scholarship = $request->stu_scholarship;
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