<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_institutions';
    protected $primaryKey = 'institution_id';

    protected $fillable = [
        'institution_id',
        'institution_kh',
        'institution_en',
        'active',
    ];


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
