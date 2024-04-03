<?php
namespace App\Http\Controllers;
use App\Models\Pay_Times;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class PayTimesController extends Controller
{
    public function index($proj_id, $use_id)
    {
        $paytimes = Pay_Times::all();
        return response()->json([
            'status' => true,
            'data' => $paytimes,
        ],200);
    }
    public function store(Request $request, $proj_id, $use_id)
    {
 
                 $rules = [
                     'pay_tim_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
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
                    Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Pay Times",3,$proj_id,$use_id);
                    return response()->json([
                      'status' => True,
                      'message' => "The pay type '". $paytimes->pay_tim_name ."' has been added succesfully."
                    ],200);
                }
        }
    public function show($proj_id, $use_id,$id)
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
    public function update(Request $request, $proj_id, $use_id, $id)
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
                    ];
                    $validator = Validator::make($request->input(), $rules);
                    if ($validator->fails()) {
                        return response()->json([
                          'status' => False,
                          'message' => $validator->errors()->all()
                        ],400);
                    }else{
                        $paytimes->pay_tim_name = $request->pay_tim_name;
                        $paytimes->save();
                        Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla pay times",3,$proj_id,$use_id);
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