<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Promotion extends Model
{
    use HasFactory;
    protected $table = 'promotions';
    protected $primaryKey = 'pro_id';
    protected $fillable = [
        'pro_name',
        'pro_group'
    ];
    public $timestamps = false;

    public static function search($promotions){
        $promotion = DB::table('promotions as p')
        ->where('p.pro_id', '=', $promotions)
        ->first();
        return $promotion;
    }
}