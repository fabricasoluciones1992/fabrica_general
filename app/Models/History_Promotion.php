<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History_Promotion extends Model
{
    use HasFactory;
    protected $table = 'history_promotions';
    protected $primaryKey = 'his_pro_id';
    protected $fillable = [
        'pro_id',
        'stu_id'
    ];
    public $timestamps = false;

    public static function select(){
        $history_promotions = History_Promotion::leftJoin('students', 'history_promotions.stu_id', '=', 'students.stu_id')
        ->leftJoin('promotions', 'history_promotions.pro_id', '=', 'promotions.pro_id')
        ->Join('persons','students.per_id','=',"persons.per_id")
        ->select('history_promotions.his_pro_id', 'persons.per_name','promotions.pro_name')
        ->get();
        return $history_promotions;
    }
    public static function search($history_promotions){
        $promotion = DB::table('history_promotions as hp')
        ->select('pro.pro_name', 'p.per_name')
        ->join('students as s', 'hp.stu_id', '=', 's.stu_id')
        ->join('persons as p', 's.per_id', '=', 'p.per_id')
        ->join('promotions as pro', 'hp.pro_id', '=', 'pro.pro_id')
        ->where('hp.his_pro_id', '=', $history_promotions->his_pro_id)
        ->first();
        return $promotion;
    }

    public static function searchPromotions($history_Promotion){
        $history_promotions = History_Promotion::leftJoin('students', 'history_promotions.stu_id', '=', 'students.stu_id')
        ->leftJoin('promotions', 'history_promotions.pro_id', '=', 'promotions.pro_id')
        ->Join('persons','students.per_id','=',"persons.per_id")
        ->select('history_promotions.his_pro_id', 'persons.per_name','promotions.pro_name')
        ->where('students.per_id','=',$history_Promotion)
        ->first();
        return $history_promotions;
    }
}