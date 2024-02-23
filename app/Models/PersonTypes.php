<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonTypes extends Model
{
    use HasFactory;

    protected $primaryKey = 'per_typ_id';

    protected $table = "person_types";

    protected $fillable = [
        'per_typ_name',
    ];

    public $timestamps = false;
}
