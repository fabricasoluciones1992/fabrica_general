<?php
namespace App\Http\Controllers;
use App\Models\Contact_Companies_Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
 
class ContactCompaniesTypesController extends Controller
{
    public function index()
    {
        try{
        $contact_companies_types = Contact_Companies_Types::all();
        return response()->json([
        'status' => true,
        'data' => $contact_companes_types,
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
                'con_com_typ_name' =>'required|unique:contact_companies_types|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {

                return response()->json([
                'status' => False,
                'message' => $validator->errors()->all()
                ]);
            }else{
                $contact_companies_types = new Contact_Companies_Types($request->input());
                $contact_companies_types->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla contact companies types",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'message' => "The process '". $contact_companies_types->con_com_typ_name ."' has been added succesfully."
                ],200);
            }
    }
    public function show($contact_Companies_Types)
    {
        $contact_companies_type = Contact_Companies_Types::find($contact_Companies_Types);
        if(!$contact_companies_type){
            return response()->json([
                'status' => false,
                'data' => ['message'=>'Could not find the contact companies types you are looking for'],
            ],400);
        }else{
            return response()->json([
                'status' => true,
                'data' => $contact_companies_type,
            ],200);
        }
    }
    public function update(Request $request, $contact_Companies_Types)
    {
            $rules = [
                'con_com_typ_name' =>'required|string|regex:/^[A-ZÑÁÉÍÓÚÜ ]+$/u',
                'use_id' =>'required|integer|exists:users'
            ];
            $validator = Validator::make($request->input(), $rules);
            $validate = Controller::validate_exists($request->con_com_typ_name, 'contact_companies_types', 'con_com_typ_name','con_com_typ_id', $contact_Companies_Types);

            if ($validator->fails()||$validate==0) {
                $msg = ($validate == 0) ? "value tried to register, it is already registered." : $validator->errors()->all();
                return response()->json([
                'status' => False,
                'message' => $msg
                ]);
            }else{
                $contact_companies_type = Contact_Companies_Types::find($contact_Companies_Types);
                $contact_companies_type->con_com_typ_name = $request-> con_com_typ_name;
                $contact_companies_type->save();
                Controller::NewRegisterTrigger("Se realizo una inserción en la tabla contact companies types",3,$request->use_id);
                return response()->json([
                    'status' => true,
                    'data' => "The contact companies types with ID: ". $contact_companies_type->pro_typ_id." has been updated to '".$contact_companies_type->con_com_typ_name."' succesfully.",
                ],200);
            }
    }
    public function destroy(Contact_Companies_Types $contact_Companies_Types)
    {
        return response()->json([
            'status' => false,
            'message' => "Functions not available"
        ],400);
    }
}