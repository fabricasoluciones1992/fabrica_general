<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Contract_types extends Model
{
    use HasFactory;
    protected $table = 'contract_types';
    protected $primaryKey = 'con_typ_id';
    protected $fillable = ['con_typ_name'];
    public $timestamps = false;
}