<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Promotion extends Model
{
    use HasFactory;
    protected $table = 'promotions';
    protected $primaryKey = 'pro_id';
    protected $fillable = [
        'pro_promotion',
        'pro_group'
    ];
    public $timestamps = false;
}