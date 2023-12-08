<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'keywords',
        'excluded_keywords',
        'content',
        'status'
    ];
}
