<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Pay_Times extends Model
{
    use HasFactory;
    protected $table = 'pay_times';
    protected $primaryKey = 'pay_tim_id';
    protected $fillable = [
        'pay_tim_name',
    ];
    public $timestamps = false;
}
 