<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $district_id
 */
class Commune extends Model
{
    protected $table = 'sys_location_communes';
    protected $primaryKey = 'com_code';

    public $incrementing = false;
    public $timestamps = false;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $fillable = ['com_code', 'dis_code', 'name_en', 'name_kh', 'note', 'active'];

    /**
     * Get the district
     */
    public function district()
    {
        return $this->belongsTo('App\Models\District', 'dis_code');
    }

    /**
     * Get the villages
     */
    public function villages()
    {
        return $this->hasMany(Village::class, 'com_code');
    }

    public function residencemembers()
    {
		return $this->hasMany('App\Models\Residencemember');
       
    }
    
    /**
     * Get only active commune
     *
     * Query Scope
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
