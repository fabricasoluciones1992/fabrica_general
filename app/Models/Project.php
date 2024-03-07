<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'proj_id';
    protected $fillable = [
        'proj_name',
        'are_id',
    ];
    public $timestamps = false;

    public static function select(){
        $projects = DB::select("SELECT projects.proj_id, projects.proj_name, areas.are_name,projects.are_id FROM projects 
        INNER JOIN areas ON projects.are_id = areas.are_id");
        return $projects;
    }
    public static function search($id){
        $project = DB::select("SELECT projects.proj_id, projects.proj_name, areas.are_name,projects.are_id FROM projects 
        INNER JOIN areas ON projects.are_id = areas.are_id 
        WHERE projects.proj_id = $id");
        return $project[0];
    }
}
