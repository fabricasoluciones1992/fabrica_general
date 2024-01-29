<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function index()
    {
        $news = DB::select("SELECT news.new_id,news.new_date,new_types.new_typ_type,projects.proj_name,users.use_mail,persons.per_name FROM news
        INNER JOIN new_types ON news.new_typ_id = new_types.new_typ_id
        INNER JOIN projects ON news.proj_id = projects.proj_id
        INNER JOIN users ON news.use_id = users.use_id
        INNER JOIN persons ON users.use_id = persons.use_id;
        ");
        return response()->json([
           'status' => true,
            'data' => $news
        ],200);
    }

    public function store(Request $request)
    {
        // return $request;
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
            return response()->json([
             'status' => True,
             'message' => "The news: ".$news->new_date." success has been created."
            ],200);
        }
    }
    public function show($id)
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
            return response()->json([
               'status' => true,
                'data' => $new
            ]);
        }
    }
    public function update(Request $request,$id)
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
                return response()->json([
                  'status' => True,
                  'message' => "The news: ".$new->new_date." has been updated."
                ],200);
            }
        }
    }
    public function destroy(News $news)
    {
        return response()->json([
            'status' => false,
            'message' => "Funcion no disponible"
         ],400);
    }
}
