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
    
        $transformedNews = $news->map(function ($item) {
            return [
                'id' => $item->new_id,
                'Fecha' => $item->new_date,
                'Descripcion' => $item->new_description,
                'Id novedad' => $item->new_typ_id,
                'Nombre novedad' => $item->new_typ_name,
                'Id proyecto' => $item->proj_id,
                'Nombre proyecto' => $item->proj_name,
                'Id del usuario' => $item->use_id,
                'Correo usuario' => $item->use_mail,
                'nombre persona' => $item->per_name,
            ];
        });
    
        return $transformedNews;
    }
    
}
