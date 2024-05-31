<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class history_scholarships extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'his_sch_id';

    protected $table = "history_scholarships";

    protected $fillable = [
        'sch_id',
        'stu_id',
        'his_sch_start',
        'his_sch_end'
    ];
    public static function select(){
        $hScholar = DB::select("
                SELECT 
            hs.his_sch_id,
            hs.his_sch_start,
            hs.his_sch_end,
            sc.sch_id,
            sc.sch_name,
            sc.sch_description,
            st.per_id,
            st.per_name,
            st.per_lastname,
            st.per_document,
            st.doc_typ_id,
            st.doc_typ_name,
            st.use_mail
        FROM 
            history_scholarships hs
        INNER JOIN 
            viewStudents st ON st.stu_id = hs.stu_id
        INNER JOIN 
            scholarships sc ON sc.sch_id = hs.sch_id
            ORDER BY
        hs.his_sch_id ASC;");
        return $hScholar;
    }
    public static function search($id){
        $hScholar = DB::select("
        SELECT 
            hs.his_sch_id,
            hs.his_sch_start,
            hs.his_sch_end,
            sc.sch_id,
            sc.sch_name,
            sc.sch_description,
            st.per_id,
            st.per_name,
            st.per_lastname,
            st.per_document,
            st.doc_typ_id,
            st.doc_typ_name,
            st.use_mail
        FROM 
            history_scholarships hs
        INNER JOIN 
            viewStudents st ON st.stu_id = hs.stu_id
        INNER JOIN 
            scholarships sc ON sc.sch_id = hs.sch_id

        WHERE hs.his_sch_id=$id");
        return $hScholar;
    }
    public $timestamps = false;
}
