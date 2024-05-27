<?php
 
namespace App\Http\Controllers;
 
use App\Models\MonetaryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MonetaryStateController extends Controller
{
    public function index()
    {
        try{
        $monState = MonetaryState::all();
        return response()->json([
            'status' => true,
            'data' => $monState
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => false,
            'message' => $th
        ],500);
    }
    }

    public function store(Request $request)
    {
            $rules = [
                'mon_sta_name' =>'required|unique:monetary_states|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                ]);
            } else {
                $monState = new MonetaryState($request->input());
                $monState->save();
                Controller::NewRegisterTrigger("An insertion was made in the monetary states table", 3, $request->use_id);
                return response()->json([
                    'status' => True,
                    'message' => "The economic state type '".$monState->mon_sta_name."' has been created successfully."
                ], 200);
            }
}

    public function show($id)
    {
        $monState = MonetaryState::find($id);
        if ($monState == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The requested economic state was not found']
            ], 400);
        } else {
            return response()->json([
                'status' => true,
                'data' => $monState
            ]);
        }
}

    public function update(Request $request, $id)
    {
        $monState = MonetaryState::find($id);
            if ($monState == null) {
                return response()->json([
                    'status' => false,
                    'data' => ['message' => 'The requested economic state was not found']
                ], 400);
            } else {
                $rules = [
                    'mon_sta_name' =>'required|string|min:1|max:50|regex:/^[A-ZÑÁÉÍÓÚÜ\s]+$/u',
                    'use_id' =>'required|integer|exists:users'
                ];
                $validator = Validator::make($request->input(), $rules);
                $validate = Controller::validate_exists($request->mon_sta_name, 'monetary_states', 'mon_sta_name','mon_sta_id', $id);

                if ($validator->fails()||$validate==0) {
                    $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                    return response()->json([
                        'status' => False,
                        'message' => $msg
                    ]);
                } else {
                    $monState->mon_sta_name = $request->mon_sta_name;
                    $monState->save();
                    Controller::NewRegisterTrigger("An update was made in the monetary states table", 1, $request->use_id);
 
                    return response()->json([
                        'status' => True,
                        'message' => "The economic state '".$monState->mon_sta_name."' has been updated successfully."
                    ], 200);
                }
            }
}
 
    public function destroy($proj_id,$use_id, $id)
    {
        
                return response()->json([
                    'status' => false,
                    'message' => 'Function not available'
                ]);
            
    }
}