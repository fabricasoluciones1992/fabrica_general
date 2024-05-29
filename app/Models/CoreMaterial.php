<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CoreMaterial extends Model
{
    use HasFactory;
    protected $table = "core_material";
    protected $primaryKey = "cor_mat_id";
    protected $fillable = [
        'cor_mat_name',
        'cor_mat_semester',
        'car_id'

    ];
    public $timestamps = false;

    public static function select() {
        $coreMaterials = DB::table('core_material')
        ->join('careers', 'careers.car_id', '=', 'core_material.car_id')
        ->select('core_material.cor_mat_id', 'core_material.cor_mat_name', 'core_material.cor_mat_semester', 'careers.car_id', 'careers.car_name')->get();
        return $coreMaterials;
    }

    public static function findOne($id) {
        $coreMaterials = DB::table('core_material')
        ->where('cor_mat_id', $id)
        ->get();
        return $coreMaterials;
    }
}
