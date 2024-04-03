<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $primaryKey = 'cou_id';
    protected $fillable = ['cou_name'];
    public $timestamps = false;
}
 