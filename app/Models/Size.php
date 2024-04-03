<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Size extends Model
{
    use HasFactory;
    protected $table = 'sizes';
    protected $primaryKey = 'siz_id';
    protected $fillable = ['siz_name','siz_min','siz_max'];
    public $timestamps = false;
 
    public static function index()
    {
        $sizes = Size::all();
        return response()->json([
            'status' => true,
            'data' => $sizes,
        ],200);
    }
}