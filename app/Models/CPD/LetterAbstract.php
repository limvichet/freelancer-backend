<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterAbstract extends Model
{
    use HasFactory;

    protected $table = 'letter_abstracts';
    protected $primaryKey = 'id';

    protected $guarded = [];
    protected $casts = [];
}
