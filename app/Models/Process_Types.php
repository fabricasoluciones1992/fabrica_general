<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Process_Types extends Model
{
    use HasFactory;
    protected $table = 'process_types';
    protected $primaryKey = 'pro_typ_id';
    protected $fillable = [
        'pro_typ_name'
    ];
    public $timestamps = false;
}
 