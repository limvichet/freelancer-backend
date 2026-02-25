<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningOption extends Model
{
    use HasFactory;

    protected $table = 'cpd_learning_options';
    protected $primaryKey = 'learning_option_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
