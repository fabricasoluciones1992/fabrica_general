<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LearningObjects extends Model
{
    use HasFactory;
    protected $table = "learning_objects";
    protected $primaryKey = "lea_obj_id";
    protected $fillable = [
        'lea_obj_object',
        'cor_mat_id',

    ];
    public $timestamps = false;

    public static function select() {
        $learningObjects = DB::table('learning_objects as le')
        ->join("core_material as co",'co.cor_mat_id','=','le.cor_mat_id')
        ->select('le.*', 'co.*')
        ->get();
        return $learningObjects;
    }

    public static function findOne($id) {
        $learningObjects = DB::table('learning_objects')
        ->join("core_material as co",'co.cor_mat_id','=','le.cor_mat_id')
        ->select('le.*', 'co.*')
        ->where('lea_obj_id', $id)
        ->get();
        return $learningObjects;
    }
}
