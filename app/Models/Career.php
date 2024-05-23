<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Career extends Model
{
    use HasFactory;
    protected $table = 'careers';
    protected $primaryKey = 'car_id';
    protected $fillable = [
        'car_name',
        'car_typ_id'
    ];
    public $timestamps = false;

    public static function select(){
        $careers = Career::join('career_types', 'careers.car_typ_id', '=', 'career_types.car_typ_id')
        ->select('careers.car_id', 'careers.car_name', 'career_types.car_typ_id', 'career_types.car_typ_name')
        ->get();
        return $careers;
    }

    public static function search($id){
        $careers = Career::join('career_types', 'careers.car_typ_id', '=', 'career_types.car_typ_id')
        ->select('careers.car_name', 'career_types.car_typ_name')
        ->where('careers.car_id', '=', $id)
        ->first();
        return $careers;
    }
}