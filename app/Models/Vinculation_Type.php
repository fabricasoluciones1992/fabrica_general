<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Vinculation_Type extends Model
{
    use HasFactory;
    protected $table = 'vinculation_types';
    protected $primaryKey = 'vin_typ_id';
    protected $fillable = [
        'vin_typ_name'
    ];
    public $timestamps = false;
}