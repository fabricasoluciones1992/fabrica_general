<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function index(){
        try {
            $news = News::all();
            return response()->json([
                'status' => true,
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }

    public function show($date){
        try {
            $news = Db::table('ViewNews')->where('new_date', 'like',"%$date%")->get();
            return response()->json([
                'status' => true,
                'data' => $news
            ]);
        } catch (\Throwable $th) {
            return response()->json([
              'status' => false,
              'message' => "Error occurred while found elements"
            ],500);
        }
    }
}
