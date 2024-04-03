<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Pay_Types extends Model
{
    use HasFactory;
    protected $table = 'pay_types';
    protected $primaryKey = 'pay_typ_id';
    protected $fillable = [
        'pay_typ_name',
    ];
    public $timestamps = false;
 
}