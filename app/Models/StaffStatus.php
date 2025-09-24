<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffStatus extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_status';

    protected $primaryKey = 'status_id';

    public $timestamps = false;

    protected $fillable = ['status_id', 'status_kh', 'status_en'];

    public function scopeActive($query)
    {
    	return $query->where('active', 1);
    }

}
