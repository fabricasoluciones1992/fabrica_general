<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTypes extends Model
{
    use HasFactory;

    protected $primaryKey = 'doc_typ_id';

    protected $fillable = [
        'doc_typ_name',
    ];

    public $timestamps = false;
}
