<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academic extends Model
{
    use HasFactory;

    protected $table = 'sys_location_academic';

    protected $primaryKey = 'year_id';

    public $timestamps = false;

    protected $fillable = ['year_id', 'year_kh', 'year_en', 'cur_year', 'start_date', 'end_date'];

    // Current Academic Year
    public function scopeCurrent($query)
    {
        return $query->where('cur_year', 1);
    }
}
