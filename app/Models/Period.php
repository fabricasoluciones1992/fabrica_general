<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Period extends Model
{
    use HasFactory;
    protected $table = 'periods';
    protected $primaryKey = 'peri_id';
    protected $fillable = [
        'peri_name',
        'peri_start',
        'peri_end',
        'pha_id',
    ];
    public $timestamps = false;
}