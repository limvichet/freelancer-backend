<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_qualifications';

    protected $primaryKey = 'qualification_id';

    public $timestamps = false;

    protected $fillable = [
        'qualification_id', 
        'qualification_kh', 
        'qualification_en', 
        'qualification_hierarchy'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
