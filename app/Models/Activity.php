<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Activity extends Model
{
    use HasFactory;
    protected $table = 'activities';
    protected $primaryKey = 'acti_id';
    protected $fillable = ['acti_name',
                            'acti_code'];
    public $timestamps = false;
}