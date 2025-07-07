<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    protected $table = 'sys_location_provinces';

    protected $primaryKey = 'pro_code';

    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = ['pro_code', 'name_kh', 'name_en', 'active'];

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
