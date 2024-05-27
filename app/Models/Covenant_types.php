<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
 
class Covenant_types extends Model
{
    use HasFactory;
    protected $table = 'covenant_types';
    protected $primaryKey = 'cov_typ_id';
    protected $fillable = ['cov_typ_name'];
    public $timestamps = false;
}