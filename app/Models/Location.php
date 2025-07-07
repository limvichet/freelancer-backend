<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'sys_locations';

    protected $primaryKey = 'location_code';

    // protected $fillable = [
    // 	'location_code', 'emis_code', 'pro_code', 'dis_code', 'com_code', 'vil_code',
    // 	'location_kh', 'location_en', 'location_his', 'region_id', 'location_type_id',
    //     'equal_gd', 'location_province', 'sub_location', 'parent_location_code',
    // 	'prokah', 'prokah_num', 'library', 'resource_center', 'building_num',
    // 	'distance_to_poe', 'multi_level_edu', 'technical_school',
    //     'school_annex', 'main_school', 'disadvantage', 'temporary_code', 'ref_doc',
    //     'created_by', 'updated_by','schoolclaster',
    // ];

    public $casts = [
        'location_code' => 'string',
        'pro_code' => 'string',
        'dis_code' => 'string',
        'com_code' => 'string',
        'vil_code' => 'string',
    ];

    // Get location type
    public function locationType()
    {
    	return $this->belongsTo('App\Models\LocationType', 'location_type_id');
    }

    // Get location histories
    // public function locationHistories()
    // {
    // 	return $this->hasMany('App\Models\LocationHistory', 'location_code');
    // }

    // Get current location history
    // public function currentLocationHistory()
    // {
    // 	return $this->hasOne('App\Models\LocationHistory', 'location_code')->orderBy('year_id', 'desc');
    // }


    // Get region info
    public function region()
    {
    	return $this->belongsTo('App\Models\Region', 'region_id');
    }


    // Get offices
    // public function offices()
    // {
    // 	return $this->belongsToMany(
    // 			'App\Models\Office', 'sys_admin_offices', 'location_code', 'office_id'
    // 		);
    // }

    public function getLocationCodesAttribute() {
        return [
            'pro_code' => substr($this->location_code, 0, 2),
            'dis_code' => substr($this->location_code, 2, 2),
            'com_code' => substr($this->location_code, 4, 2),
            'vil_code' => substr($this->location_code, 6, 2),
            'emis_code' => substr($this->location_code, 8, 3),
        ];
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'pro_code');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District', 'dis_code');
    }

    public function commune()
    {
        return $this->belongsTo('App\Models\Commune', 'com_code');
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village', 'vil_code');
    }

    public function getLocationCommuneAttribute()
    {
        return $this->location_kh . (!is_null($this->commune) ? ' - ឃុំ/សង្កាត់៖'.$this->commune->name_kh : '');
    }
    
    public function getLocationProvinceAttribute()
    {
        return $this->location_kh . (!is_null($this->province) ? ' - ខេត្ត៖' . $this->province->name_kh : '');
    }
}
