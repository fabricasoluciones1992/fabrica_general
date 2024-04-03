<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Learning_Objects extends Model
{
    use HasFactory;
    protected $table = 'learning_objects';
    protected $primaryKey = 'lea_obj_id';
    protected $fillable = [
        'lea_obj_object',
        'lea_obj_subject',
        'lea_obj_semester',
        'cof_id'
    ];
    public $timestamps = false;
}