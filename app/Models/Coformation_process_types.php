<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Coformation_process_types extends Model
{
    use HasFactory;
    protected $table = 'coformation_process_types';
    protected $primaryKey = 'cof_pro_typ_id';
    protected $fillable = [
        'cof_pro_typ_name',
    ];
    public $timestamps = false;
}
 