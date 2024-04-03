<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Career_Types extends Model
{
    use HasFactory;
    protected $table = 'career_types';
    protected $primaryKey = 'car_typ_id';
    protected $fillable = [
        'car_typ_name'
    ];
    public $timestamps = false;
}