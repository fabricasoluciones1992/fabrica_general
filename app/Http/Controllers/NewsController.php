<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class NewsController extends Controller
{
    public function index($proj_id,$use_id,$column,$data)
    {
        try {
            $news = ($column == 'new_id') ? News::OrderBy($column, 'DESC')->paginate(100) : News::OrderBy($column, 'DESC')->where($column, '=', $data)->paginate(100);
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
}
