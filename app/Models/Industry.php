<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Industry extends Model
{
    use HasFactory;
    protected $table = 'industries';
    protected $primaryKey = 'ind_id';
    protected $fillable = ['ind_name'];
    public $timestamps = false;
}