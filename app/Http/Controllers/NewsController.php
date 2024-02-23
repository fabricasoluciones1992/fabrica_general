<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function index($proj_id,$use_id)
    {
        try {
            $news = DB::select("SELECT news.new_id,news.new_date,new_types.new_typ_type,projects.proj_name,users.use_mail,persons.per_name FROM news
            INNER JOIN new_types ON news.new_typ_id = new_types.new_typ_id
            INNER JOIN projects ON news.proj_id = projects.proj_id
            INNER JOIN users ON news.use_id = users.use_id
            INNER JOIN persons ON users.use_id = persons.use_id;
            ");
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla News",4,$proj_id,$use_id);
            return response()->json([
               'status' => true,
                'data' => $news
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }

    }

    public function store($proj_id,$use_id,Request $request)
    {
        $rules = [
            'new_date' =>'required|date',
            'new_typ_id' =>'required|integer',
            'proj_id' =>'required|integer',
            'use_id' =>'required|integer'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
             'status' => False,
             'message' => $validator->errors()->all()
            ]);
        }else{
            $news = new News($request->input());
            $news->save();
            Controller::NewRegisterTrigger("Se creo un registro en la tabla News : $request->new_date, $request->new_typ_id,$request->proj_id, $request->use_id ",3,$proj_id,$use_id);
            return response()->json([
             'status' => True,
             'message' => "The news: ".$news->new_date." success has been created."
            ],200);
        }
    }
    public function show($proj_id,$use_id,$id)
    {
        $new =  DB::select("SELECT news.new_id,news.new_date,new_types.new_typ_type,projects.proj_name,users.use_mail,persons.per_name FROM news
        INNER JOIN new_types ON news.new_typ_id = new_types.new_typ_id
        INNER JOIN projects ON news.proj_id = projects.proj_id
        INNER JOIN users ON news.use_id = users.use_id
        INNER JOIN persons ON users.use_id = persons.use_id WHERE $id = news.new_id;
        ");
        if ($new == null) {
            return response()->json([
                'status' => false,
                "data" => ['message' => 'The searched novelty was not found']
            ],400);
        }else{
            Controller::NewRegisterTrigger("Se realizo una busqueda en la tabla News por dato especifico: $id",4,$proj_id,$use_id);
            return response()->json([
               'status' => true,
                'data' => $new
            ]);
        }
    }
    public function update($proj_id,$use_id,Request $request,$id)
    {
        $new = News::find($id);
        if ($new == null) {
            return response()->json([
                'status' => false,
                'data' => ['message' => 'The searched novelty was not found']
            ],400);
        }else{
            $rules = [
                'new_date' =>'required|date',
                'new_typ_id' =>'required|integer',
                'proj_id' =>'required|integer',
                'use_id' =>'required|integer'
            ];
            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json()([
                   'status' => False,
                  'message' => $validator->errors()->all()
                ]);
            }else{
                $new->new_date = $request->new_date;
                $new->new_typ_id = $request->new_typ_id;
                $new->proj_id = $request->proj_id;
                $new->use_id = $request->use_id;
                $new->save();
                Controller::NewRegisterTrigger("Se realizo una Edicion de datos en la tabla news del dato: $id con el dato: $request->new_date, $request->new_typ_id,$request->proj_id, $request->use_id",1,$proj_id,$use_id);
                return response()->json([
                  'status' => True,
                  'message' => "The news $id has been updated."
                ],200);
            }
        }
    }
    public function destroy(News $news)
    {
        return response()->json([
            'status' => false,
            'message' => "FUNCTION NOT AVAILABLE"
         ],400);
    }
}
