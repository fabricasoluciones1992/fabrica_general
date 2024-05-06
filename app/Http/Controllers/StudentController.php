<?php
namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $students = DB::table('viewStudents')->get();
        return response()->json([
            'status' => true,
            'data' => $students,
        ],200);
    }

    public function indexAmount()
    {
        $students = DB::table('viewStudents')->orderBy('stu_id', 'desc')->take(50)->get();
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
        'stu_scholarship' => 'required',
        'per_id' => 'required|integer',
        'loc_id' => 'required|integer',
        'mon_sta_id' => 'required|integer',
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
            'status' => False,
            'message' => $validator->errors()->all()
            ]);
        }else{
        $person = Student::where('per_id','=',$request->per_id)->get();
        if ($person != "[]") {
            return response()->json([
                'status' => False,
                'message' => "the student already exists"
            ]);
        }
        $students = new Student($request->input());
        $students->save();
        $person = DB::table('persons')->where('per_id','=',$students->per_id)->first();
        Controller::NewRegisterTrigger("Se realizo una inserción en la tabla students",3,6,$request->use_id);
        return response()->json([
            'status' => true,
            'stu_id' => $students->stu_id,
            'message' => "The student '". $person->per_name ."' has been added succesfully."
        ],200);}

   }
    public function show($student)
    {
        $students = DB::table('viewStudents')->where('per_document','=', $student)->first();
        $careers = DB::select("SELECT careers.car_name FROM history_careers INNER JOIN careers ON careers.car_id = history_careers.car_id WHERE stu_id = $students->stu_id");
        $promotions = DB::select("SELECT promotions.pro_name, promotions.pro_group FROM history_promotions INNER JOIN promotions ON promotions.pro_id = history_promotions.pro_id WHERE stu_id = $students->stu_id");
        $students->careers = $careers;
        $students->promotions = $promotions;
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
        'per_id' => 'required|integer',
        'loc_id' => 'required|integer',
        'mon_sta_id' => 'required|integer',
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
        Controller::NewRegisterTrigger("Se realizo una edición en la tabla students",1,6,$request->use_id);
        return response()->json([
            'status' => true,
            'data' => "The student with ID: ". $students->stu_id." has been updated to '" . $person->per_name ."' succesfully.",
        ],200);}
        }
    public function destroy(Student $student)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}