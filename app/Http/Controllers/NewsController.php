<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function index(){
        // try {
            $news = News::select();
            return response()->json([
                'status' => true,
                'data' => $news
            ]);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //       'status' => false,
        //       'message' => "Error occurred while found elements"
        //     ],500);
        // }
    }

    public function show($type,$proj_id,$date){
        try {
            $news = DB::table('ViewNews')
            ->whereDate('new_date', $date)
            ->where('new_typ_id', $type)
            ->where('proj_id', $proj_id)
            ->get();        
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
