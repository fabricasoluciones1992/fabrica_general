<?php
namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index($proj_id, $use_id)
    {
        $students = DB::table('ViewStudent')->get();
        Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla students",4,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'data' => $students,
        ],200);
    }
    public function store(Request $request,$proj_id, $use_id)
    {
        $rules = [
        'stu_stratum' => 'required',
        'stu_code' => 'required|numeric',
        'stu_journey' => 'required',
        'stu_scholarship' => 'required',
        'stu_military' => 'required|numeric|max:9999999999',
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
        $students = new Student($request->input());
        $students->save();
        $person = DB::table('persons')->where($students->per_id, "=", "per_id")->first();
        Controller::NewRegisterTrigger("Se realizo una inserción en la tabla students",3,$proj_id,$use_id);
        return response()->json([
            'status' => true,
            'message' => "The student '". $person->per_name ."' has been added succesfully."
        ],200);}

   }
    public function show($proj_id, $use_id, $student)
    {
        $students = DB::table('ViewStudent')->where('stu_id', $student)->first();
        if(!$students){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the students you are looking for'],
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda de un dato especifico en la tabla students",4,$proj_id,$use_id);
            return response()->json([
                'status' => true,
                'data' => $students,
            ],200);
        }
    }
    public function update(Request $request, $proj_id, $use_id, $student)
    {
        $rules = [
        'stu_stratum' => 'required',
        'stu_code' => 'required|numeric',
        'stu_journey' => 'required',
        'stu_scholarship' => 'required',
        'stu_military' => 'required|numeric|max:9999999999',
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
        $person = DB::table('persons')->where($students->per_id, "=", "per_id")->first();
        Controller::NewRegisterTrigger("Se realizo una edición en la tabla students",1,$proj_id,$use_id);
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