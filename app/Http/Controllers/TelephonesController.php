<?php

namespace App\Http\Controllers;

use App\Models\telephone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class TelephonesController extends Controller
{
    public function index()
    {
        try{
        $telephone = telephone::select();
        return response()->json([
            'status' => true,
            'data' => $telephone
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
            'tel_number' =>['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:7', 'max:15'],
            'tel_description' =>'string|max:255',
            'per_id' =>'required|integer|exists:persons',
            'use_id' =>'required|integer|exists:users'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
              'status' => False,
              'message' => $validator->errors()->all()
            ]);
        }else{
            $telephone = new telephone($request->input());
            $telephone->save();
            Controller::NewRegisterTrigger("Se realizó una inserción de datos en la tabla telephones",3,$request->use_id);
            return response()->json([
              'status' => True,
              'message' => "The Telephone number ".$telephone->tel_number." has been added succesfully.",
              'data' => $telephone->tel_id
            ],200);
        }
    }
    public function show($id)
    {
        $telephone = telephone::search($id);
        if ($telephone == null) {   
            return response()->json([
               'status' => false,
                'data' => ['message' => 'Could not find the telephone number you are looking for']
            ],400);
        }else{
            return response()->json([
               'status' => true,
                'data' => $telephone
            ],200);
        }
    }
    public function update(Request $request, $id)
    {
        $telephone = Telephone::find($id);
        if ($telephone == null) {
            return response()->json([
              'status' => false,
                'data' => ['message' => 'Could not find required telephone']
            ],400);
        }else{
            $rules = [
                'tel_number' =>['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:7', 'max:15'],
                'tel_description' =>'string|max:255',
                'per_id' =>'required|integer|exists:persons',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                  'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $telephone->tel_number = $request->tel_number;
                $telephone->tel_description = $request->tel_description;
                $telephone->per_id = $request->per_id;
                $telephone->save();
                Controller::NewRegisterTrigger("Se realizó una actualización de datos en la tabla telephones",1,$request->use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The telephone ".$telephone->tel_number." has been updated succesfully."
                ],200);
            }
        }   
    }
    public function destroy()
    {
        return response()->json([
            'status' => false,
            'message' => "You have no permission to delete this"
         ],400);
    }
}
