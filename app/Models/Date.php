<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Date extends Model
{
    protected $table = 'sys_dates';
    protected $primaryKey = 'id';
    protected $fillable = ['date', 'lunar_date', 'soriyakitek_date'];
}
