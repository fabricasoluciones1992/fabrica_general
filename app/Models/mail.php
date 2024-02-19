<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mail extends Model
{
    use HasFactory;

    protected $primaryKey = 'mai_id';

    protected $fillable = [
        'mai_mail',
        'mai_description',
        'per_id'
    ];

    public $timestamps = false;
}
