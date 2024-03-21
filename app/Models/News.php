<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'new_id';

    protected $fillable = [
        'new_date',
        'new_type_id',
        'proj_id',
        'use_id',
    ];

    public $timestamps = false;

    public static function select(){
        $news = DB::table('ViewNews')->select(['new_id','new_date','new_description','new_typ_id','new_typ_name','proj_id','proj_name','use_id','use_mail','per_name'])->get();
        return $news;
    }
}
