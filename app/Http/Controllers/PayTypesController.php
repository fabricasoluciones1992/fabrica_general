<?php
namespace App\Http\Controllers;
use App\Models\Pay_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class PayTypesController extends Controller
{
    public function index()
    {
        try{
        $paytype = Pay_Types::all();
        return response()->json([
            'status' => true,
            'data' => $paytype,
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
                     'pay_typ_name' =>'required|string|unique:pay_types|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                     'use_id' =>'required|integer|exists:users'
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                      'status' => False,
                      'message' => $validator->errors()->all()
                    ],400);
                }else{
                    $paytype = new Pay_Types($request->input());
                    $paytype->save();
                    Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla Pay Types",3,6,$request->use_id);
                    return response()->json([
                      'status' => True,
                      'message' => "The pay type '". $paytype->pay_typ_name ."' has been added succesfully."
                    ],200);
                }
        }
    public function show($id)
    {
        $paytype = Pay_Types::find($id);
        if ($paytype == null) {
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the Pay Type you are looking for']
            ],400);
        }else{
             return response()->json([
               'status' => true,
                'data' => $paytype
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
                $paytype = Pay_Types::find($id);
                if ($paytype == null) {
                    return response()->json([
                      'status' => false,
                        'data' => ['message' => 'Could not find required pay type']
                    ],400);
                }else{
                    $rules = [
                        'pay_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                        'use_id' =>'required|integer|exists:users'
                    ];
                    $validator = Validator::make($request->input(), $rules);
                    $validate = Controller::validate_exists($request->pay_typ_name, 'pay_types', 'pay_typ_name', 'pay_typ_id', $id);
                    if ($validator->fails() || $validate == 0) {
                        $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                        return response()->json([
                          'status' => False,
                          'message' => $msg
                        ],400);
                    }else{
                        $paytype->pay_typ_name = $request->pay_typ_name;
                        $paytype->save();
                        Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla pay types",3,6,$request->use_id);
                        return response()->json([
                          'status' => True,
                          'message' => "The pay type with ID: ". $paytype -> pay_typ_id." has been updated to '" . $paytype->pay_typ_name ."' succesfully."
                        ],200);
                    }
                }
        }
    public function destroy($id,$proj_id, $use_id)
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}