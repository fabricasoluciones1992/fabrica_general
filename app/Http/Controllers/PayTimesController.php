<?php
namespace App\Http\Controllers;
use App\Models\Pay_Times;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class PayTimesController extends Controller
{
    public function index()
    {
        try{
        $paytimes = Pay_Times::all();
        return response()->json([
            'status' => true,
            'data' => $paytimes,
        ],200);
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
                     'pay_tim_name' =>'required|string|unique:pay_times|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                     'use_id' =>'required|integer|exists:users'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                      'status' => False,
                      'message' => $validator->errors()->all()
                    ],400);
                }else{
                    $paytimes = new Pay_Times($request->input());
                    $paytimes->save();
                    Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Pay Times",3,6,$request->use_id);
                    return response()->json([
                      'status' => True,
                      'message' => "The pay type '". $paytimes->pay_tim_name ."' has been added succesfully."
                    ],200);
                }
        }
    public function show($id)
    {
        $paytimes = Pay_Times::find($id);
        if ($paytimes == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the Pay Time you are looking for']
            ],400);
        }else{
             return response()->json([
               'status' => true,
                'data' => $paytimes
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
                $paytimes = Pay_Times::find($id);
                if ($paytimes == null) {
                    return response()->json([
                      'status' => false,
                        'data' => ['message' => 'Could not find required pay type']
                    ],400);
                }else{
                    $rules = [
                        'pay_tim_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                        'use_id' =>'required|integer|exists:users'
                    ];
                    $validator = Validator::make($request->input(), $rules);
                    $validate = Controller::validate_exists($request->pay_tim_name, 'pay_times', 'pay_tim_name', 'pay_tim_id', $id);
                    if ($validator->fails() || $validate == 0) {
                        $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                        return response()->json([
                          'status' => False,
                          'message' => $msg
                        ],400);
                    }else{
                        $paytimes->pay_tim_name = $request->pay_tim_name;
                        $paytimes->save();
                        Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla pay times",3,6,$request->use_id);
                        return response()->json([
                          'status' => True,
                          'message' => "The pay time with ID: ". $paytimes -> pay_tim_id." has been updated to '" . $paytimes->pay_tim_name ."' succesfully."
                        ],200);
                    }
                }
        }
    public function destroy($id, $proj_id, $use_id)
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}