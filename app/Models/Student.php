<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;
    protected $table = 'students';
    protected $primaryKey = 'stu_id';
    protected $fillable = [
        'stu_stratum',
        'stu_code',
        'stu_journey',
        'stu_rh',
        'stu_scholarship',
        'stu_military',
        'per_id',
        'loc_id',
        'mon_sta_id'
    ];
    public $timestamps = false;

    public static function show($id){
        $student = DB::table('viewStudents')->where('per_document','=', $id)->first();
        $student = Student::find($student->stu_id);
        $student->promotion = $student->lastPromotion();
        return $student;
    }

    public function lastPromotion(){
        $data = DB::table('history_promotions')
        ->join('promotions', 'history_promotions.pro_id', '=', 'promotions.pro_id')
        ->where('history_promotions.stu_id', $this->stu_id)
        ->orderBy('history_promotions.pro_id', 'desc')
        ->first();
        return $data->pro_name ."-". $data->pro_group;
    }
}