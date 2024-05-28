<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{

    public function index()
    {
        try {
            $periods = Period::select();
            return response()->json([
                'status' => true,
                'data' => $periods
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'peri_name' => 'required|string|max:15',
            'peri_start' => 'required|date',
            'peri_end' => 'required|date',
            'pha_id' => 'required|integer|exists:phases',
            'use_id' => 'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
            ]);
        } else {
            $periods = new Period($request->input());
            $periods->save();
            Controller::NewRegisterTrigger("Se realizo una inserción en la tabla Periods",3,$request->use_id);
            return response()->json([
                'status' => True,
                'message' => "The period " . $periods->peri_name . " has been added succesfully."
            ], 200);
        }
    }
    public function show($id)
    {
        $periods = Period::search($id);
        if ($periods == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'Could not find period you are looking for']
            ], 404);
        } else {

            return response()->json([
                'status' => true,
                'data' => $periods
            ], 200);
        }
    }
    public function update(Request $request, $id)
    {
        $periods =  Period::find($id);
        if ($periods == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'Could not find required period']
            ], 404);
        } else {
            $rules = [
                'peri_name' => 'required|string|max:15',
                'peri_start' => 'required|date',
                'peri_end' => 'required|date',
                'pha_id' => 'required|integer|exists:phases',
                'use_id' => 'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $periods->peri_name = $request->peri_name;
                $periods->peri_start = $request->peri_start;
                $periods->peri_end = $request->peri_end;
                $periods->pha_id = $request->pha_id;
                $periods->save();
                Controller::NewRegisterTrigger("Se realizo una edición en la tabla Periods",1,$request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The period " . $periods->peri_name . " has been updated succesfully."
                ], 200);
            }
        }
    }
    public function destroy(Period $period)
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
        ], 400);
    }
}
