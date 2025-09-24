<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $table = 'sys_staff_positions';

    protected $primaryKey = 'position_id';

    protected $fillable = [
        'position_id',
    	'position_kh',
    	'position_en',
    	'pos_category_id',
    	'position_hierarchy',
    	'pos_level_id',
    	'created_by',
    	'updated_by'
    ];


    // Get created by user info
    public function createdBy()
    {
    	return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }


    // Get updated by user info
    public function updatedBy()
    {
    	return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
