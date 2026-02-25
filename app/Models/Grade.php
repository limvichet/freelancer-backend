<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'sys_location_grades';

    protected $primaryKey = 'grade_id';

    public $timestamps = false;

    protected $fillable = ['grade_id', 'grade_kh', 'grade_en', 'edu_level_id', 'description'];


}
