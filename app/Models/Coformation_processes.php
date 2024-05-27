<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
 
class Coformation_processes extends Model
{
    use HasFactory;
    protected $table = 'coformation_processes';
    protected $primaryKey = 'cof_proc_id';
    protected $fillable = [
        'cof_proc_status',
        'cof_proc_observation',
        'cof_pro_typ_id',
        'peri_id',
        'com_id',
        'stu_id'
    ];
    public $timestamps = false;

    public static function SelectAll(){
        $coformation_process = DB::table('coformation_processes AS cp')
        ->join('coformation_process_types AS cpt', 'cp.cof_pro_typ_id', '=', 'cpt.cof_pro_typ_id'  )
        ->join('periods AS peri', 'cp.peri_id', '=', 'peri.peri_id')
        ->join('companies AS com', 'cp.com_id', '=', 'com.com_id')
        ->join('students AS stu', 'cp.stu_id', '=', 'stu.stu_id')
        ->join('persons AS per', 'per.per_id', '=', 'stu.stu_id')
        ->select('cp.cof_proc_id', 'cp.cof_proc_observation', 'cp.cof_pro_typ_id', 'cpt.cof_pro_typ_name', 'peri.peri_id', 'peri.peri_name', 'com.com_id', 'com.com_name', 'stu.stu_id', 'per.per_name')
        ->get();
        
        return $coformation_process;
    }

    public static function ShowOne($coformation_process){
        $coformation_process = DB::table('coformation_processes AS cp')
        ->join('coformation_process_types AS cpt', 'cp.cof_pro_typ_id', '=', 'cpt.cof_pro_typ_id'  )
        ->join('periods AS peri', 'cp.peri_id', '=', 'peri.peri_id')
        ->join('companies AS com', 'cp.com_id', '=', 'com.com_id')
        ->join('students AS stu', 'cp.stu_id', '=', 'stu.stu_id')
        ->join('persons AS per', 'per.per_id', '=', 'stu.stu_id')
        ->select('cp.cof_proc_id', 'cp.cof_proc_observation', 'cp.cof_pro_typ_id', 'cpt.cof_pro_typ_name', 'peri.peri_id', 'peri.peri_name', 'com.com_id', 'com.com_name', 'stu.stu_id', 'per.per_name')
        ->where('cp.cof_proc_id', $coformation_process)
        ->first();
        
        return $coformation_process;
    }
}
 