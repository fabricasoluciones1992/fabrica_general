<?php
namespace App\Http\Controllers;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return $sizes;
    }
    public function store(Request $request)
    {
                $rules = [
                    'siz_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                    'siz_min' =>'required|numeric',
                    'siz_max' =>'required|numeric',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $sizes = new Size();
                    $sizes->siz_name = $request->siz_name;
                    $sizes->siz_min = $request->siz_min;
                    $sizes->siz_max = $request->siz_max;
                    $sizes->save();
                    Controller::NewRegisterTrigger("Se realizo una inserción en la tabla sizes",3,6,$request->use_id);
                    return response()->json([
                    'status' => true,
                    'message' => "The size '". $sizes->siz_name ."' has been added succesfully."
                ],200);}
        }
    public function show($size)
    {
        $sizes = Size::find($size);
        if(!$sizes){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the sizes you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $sizes,
            ],200);
        }
    }
    public function update(Request $request, $size)
    {
                $rules = [
                    'siz_name' =>'required|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                    'siz_min' =>'required|numeric',
                    'siz_max' =>'required|numeric',
                ];
                $validator = Validator::make($request->input(), $rules);
                if ($validator->fails()) {
                    return response()->json([
                    'status' => False,
                    'message' => $validator->errors()->all()
                    ]);
                }else{
                    $sizes = Size::find($size);
                    $sizes->siz_name = $request->siz_name;
                    $sizes->siz_min = $request->siz_min;
                    $sizes->siz_max = $request->siz_max;
                    $sizes->save();
                    Controller::NewRegisterTrigger("Se realizo una edición en la tabla sizes",1,6,$request->use_id);
                    return response()->json([
                    'status' => true,
                    'data' => "The coformador type with ID: ". $sizes -> siz_id." has been updated to '" . $sizes->siz_name ."' succesfully.",
                ],200);}
        }
    public function destroy(Size $size)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
         ],400);
    }
}