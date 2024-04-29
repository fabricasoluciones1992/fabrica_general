<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History_career extends Model
{
    use HasFactory;
    protected $table = 'history_careers';
    protected $primaryKey = 'his_car_id';
    protected $fillable = [
        'car_id',
        'stu_id'
    ];
    public $timestamps = false;

    public static function select(){
        $history_careers = History_career::join('students', 'history_careers.stu_id', '=', 'students.stu_id')
        ->join('careers', 'history_careers.car_id', '=', 'careers.car_id')
        ->join('persons', 'students.per_id', '=', 'persons.per_id')
        ->select('history_careers.his_car_id','careers.car_name','persons.per_name')
        ->get();
        return $history_careers;
    }
    public static function search($history_careers){
        $career = DB::select("SELECT c.car_name, p.per_name
        FROM history_careers hc
        INNER JOIN students s ON hc.stu_id = s.stu_id
        INNER JOIN persons p ON s.per_id = p.per_id
        INNER JOIN careers c ON hc.car_id = c.car_id
        WHERE hc.his_car_id = :his_car_id", ['his_car_id' => $history_careers->his_car_id]);
        return $career[0];
    }

    public static function search_career($History_career){
        $history_careers = History_career::join('students', 'history_careers.stu_id', '=', 'students.stu_id')
        ->join('careers', 'history_careers.car_id', '=', 'careers.car_id')
        ->join('persons', 'students.per_id', '=', 'persons.per_id')
        ->select('history_careers.his_car_id','careers.car_name','persons.per_name')
        ->where('students.per_id','=',$History_career)
        ->first();
        return $history_careers;
    }
}