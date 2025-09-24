<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_professionals';

    protected $primaryKey = 'professional_id';

    public $timestamps = false;

    protected $fillable = ['professional_id', 'professional_kh', 'professional_hierarchy', 'active'];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
