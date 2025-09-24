<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    protected $table = 'sys_location_provinces';

    protected $primaryKey = 'pro_code';

    protected $fillable = [
        'pro_code',
        'name_en',
        'name_kh',
        'reference',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public $casts = [
        'pro_code' => 'string',
    ];

    /**
     * Get the districts
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'pro_code');
    }


    /**
     * Query scope : get only active province
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
