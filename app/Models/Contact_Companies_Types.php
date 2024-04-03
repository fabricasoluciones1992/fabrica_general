<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Contact_Companies_Types extends Model
{
    use HasFactory;
    protected $table = 'contact_companies_types';
    protected $primaryKey = 'con_com_typ_id';
    protected $fillable = [
        'con_com_typ_name'
    ];
    public $timestamps = false;
}
 