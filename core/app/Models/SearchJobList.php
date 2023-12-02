<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchJobList extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'title',
        'content',
        'status',
    ];
}
