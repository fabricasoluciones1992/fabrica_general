<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class scholarships extends Model
{
    
        use HasFactory;
    
        protected $primaryKey = 'sch_id';
    
        protected $table = "scholarships";
    
        protected $fillable = [
            'sch_name',
            'sch_description'
        ];
    
        public $timestamps = false;
}
