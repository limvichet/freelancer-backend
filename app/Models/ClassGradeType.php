<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassGradeType extends Model
{
    use HasFactory;

    protected $table = 'sys_class_grade_types';

    protected $primaryKey = 'cgt_id';

    public $timestamps = false;
    protected $fillable = ['cgt_id', 'cgt_name', 'cgt_code', 'cgt_description'];


}
