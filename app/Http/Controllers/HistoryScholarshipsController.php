<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\history_scholarships;
use Illuminate\Http\Request;

class HistoryScholarshipsController extends Controller{
    public function index()
{
    $hScholar = history_scholarships::select();

    return response()->json([
        'status' => true,
        'data' => $hScholar
    ], 200);
}


public function store(Request $request)
{

        $rules = [
            'sch_id' => 'required|numeric|exists:scholarships',
            'stu_id' => 'required|numeric|exists:students',
            'his_sch_start' => 'required|date',
            'his_sch_end' => 'date',
        ];
        
        $validator = Validator::make($request->input(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $existingRecord = history_scholarships::where('sch_id', $request->sch_id)
                                              ->where('stu_id', $request->stu_id)
                                              ->first();
        if ($existingRecord) {
            return response()->json([
                'status' => false,
                'message' => 'A record with the same'
            ], 409);
        }

        $hScholar = new history_scholarships($request->input());
        $hScholar->save();

        Controller::NewRegisterTrigger("An insertion was made in the scholarships Histories table '$hScholar->his_sch_id'", 3,$request->use_id);

        return response()->json([
            'status' => true,
            'message' => "The scholarships history has been created successfully.",
            'data' => $hScholar->his_sch_id,
        ], 200);

    
}


    public function show($id)
{
    $hScholar = history_scholarships::search($id);
    if ($hScholar == null) {
        return response()->json([
            'status' => false,
            'data' => ['message' => 'The requested scholarships Histories was not found']
        ],400);
    }else{

        return response()->json([
            'status' => true,
            'data' => $hScholar
        ]);
    }
    
}


public function update(Request $request, $id)
{

    return response()->json([
        'status' => false,
        'message' => 'Function not available'
    ]);
}

    public function destroy(Request $request,$id)
    {
        
        return response()->json([
            'status' => false,
            'message' => 'Function not available'
        ]);
            
    }
}
