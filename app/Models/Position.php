<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Position extends Model
{
    use HasFactory;
    //=========IMPORTANTE ADAPTAR AL MODELO=============
    protected $primaryKey = 'pos_id';
    protected $fillable = [
        'pos_name',
        'are_id',
    ];
    public $timestamps = false;

    public static function select(){
        $positions = DB::select("SELECT positions.pos_name, positions.pos_id, areas.are_name, positions.are_id FROM positions 
        INNER JOIN areas ON positions.are_id = areas.are_id");
        return $positions;
    }
    public static function find($id){
        $position = DB::select("SELECT positions.pos_name, positions.pos_id, areas.are_name,positions.are_id FROM positions 
        INNER JOIN areas ON positions.are_id = areas.are_id 
        WHERE $id = positions.pos_id ");
        return $position[0];
    }
}
