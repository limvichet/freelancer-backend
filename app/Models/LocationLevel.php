<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationLevel extends Model
{
    use HasFactory;

    protected $table = 'sys_location_levels';

    protected $primaryKey = 'edu_level_id';

    public $timestamps = false;

    protected $fillable = ['edu_level_id', 'edu_level_kh', 'edu_level_en'];
}
