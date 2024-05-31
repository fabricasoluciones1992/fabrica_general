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
        try {
            $students_enrollments = Student_enrollments::select();
            return response()->json([
                'status' => true,
                'data' => $students_enrollments,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
    public static function store(Request $request)
    {
        $rules = [
            'stu_enr_semester' => 'required|numeric|max:7|min:1',
            'stu_enr_journey' => 'required|numeric|max:1|min:0',
            'stu_id' => 'required|exists:students',
            'peri_id' => 'required|exists:periods',
            'car_id' => 'required|exists:careers',
            'pro_id' => 'required|exists:promotions',
            'pha_id' => 'required|exists:phases'

        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $existingRecord = Student_enrollments::where('stu_enr_semester', $request->stu_enr_semester)
            ->where('stu_enr_journey', $request->stu_enr_semester)
            ->where('stu_id', $request->stu_id)
            ->where('peri_id', $request->peri_id)
            ->where('car_id', $request->car_id)
            ->where('pro_id', $request->pro_id)
            ->where('stu_enr_status', 1)
            ->first();

        if ($existingRecord) {
            return response()->json([
                'status' => false,
                'message' => 'A record with the same characteristics already exists.'
            ], 409);
        }

        $students_enrollments = new Student_enrollments();
        $students_enrollments->stu_enr_semester = $request->stu_enr_semester;
        $students_enrollments->stu_enr_journey = $request->stu_enr_journey;
        $students_enrollments->stu_id = $request->stu_id;
        $students_enrollments->peri_id = $request->peri_id;
        $students_enrollments->car_id = $request->car_id;
        $students_enrollments->pro_id = $request->pro_id;
        $students_enrollments->stu_enr_status = 1;
        $students_enrollments->stu_enr_date = now()->toDateString();
        $students_enrollments->save();

        $oldEnrollments = Student_enrollments::where('stu_id', $request->stu_id)
            ->where('stu_enr_status', 1)
            ->where('stu_enr_id', '!=', $students_enrollments->stu_enr_id)
            ->get();

        foreach ($oldEnrollments as $oldEnrollment) {
            $oldEnrollment->stu_enr_status = 0;
            $oldEnrollment->save();
        }



        $student = DB::table('viewEnrollments')->where('stu_id', $request->stu_id)->first();

        Controller::NewRegisterTrigger("Se realizo una inserción en la tabla students_enrollments", 3,$request->use_id);

        return response()->json([
            'status' => true,
            'message' => "The enrollment of student '" . $student->per_name . "' in semester '" . $students_enrollments->stu_enr_semester . "' in the period '" . $student->peri_name . "' has been added successfully.",
        ], 200);
    }

    public function show($id)
    {
        $students_enrollments = Student_enrollments::search($id);
        if ($students_enrollments == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The student enrollments requested not found'],
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $students_enrollments,
            ], 200);
        }
    }
    public function update(Request $request, $id) //pendiente o no se edita o se edita el mismo dia
    {
        $rules = [
            'stu_enr_semester' => 'required|numeric|max:7|min:1',
            'stu_enr_journey' => 'required|numeric|max:1|min:0',
            'peri_id' => 'required|exists:periods',
            'stu_id' => 'required|exists:students',
            'car_id' => 'required|exists:careers',
            'stu_enr_status' => 'required|numeric|max:2|min:0',
            'pro_id' => 'required|exists:promotions',
            'pha_id' => 'required|exists:phases'

        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $students_enrollments = Student_enrollments::find($id);
            $students_enrollments->stu_enr_semester = $request->stu_enr_semester;
            $students_enrollments->stu_enr_journey = $request->stu_enr_journey;
            $students_enrollments->peri_id = $request->peri_id;
            $students_enrollments->car_id = $request->car_id;
            $students_enrollments->pro_id = $request->pro_id;
            $students_enrollments->pha_id = $request->pha_id;
            $students_enrollments->stu_enr_status = $request->stu_enr_status;
            $students_enrollments->stu_enr_date = now()->toDateString();


            $students_enrollments->save();



            $student = DB::table('viewEnrollments')->where('stu_id', $request->stu_id)->first();
            Controller::NewRegisterTrigger("Se realizo una edición en la tabla students enrollments", 4,$request->use_id);
            return response()->json([
                'status' => true,
                'message' => "The enrollment of student '" . $student->per_name . "' in semester '" . $students_enrollments->stu_enr_semester . "' in the period '" . $student->peri_name . "' has been updated successfully.",
            ], 200);
        }
    }


    public function historyEnrollments()
    {
        try {
            $students_enrollments = Student_enrollments::inactive();
            return response()->json([
                'status' => true,
                'data' => $students_enrollments,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
    public function destroy(Request $request, $id)
    {
        $studentE = Student_enrollments::find($id);
        $newSE = ($studentE->stu_enr_status == 0) ? 1 : 0;
        $studentE->stu_enr_status = $newSE;
        $studentE->save();
        Controller::NewRegisterTrigger("An change status was made in the students enrollments table", 2, 6, $request->use_id);
        return response()->json([
            'status' => True,
            'message' => 'The requested students enrollments has been change status successfully'
        ]);
    }
}
