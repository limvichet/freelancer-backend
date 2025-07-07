<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'sys_location_districts';
    
    protected $primaryKey = 'dis_code';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = array('dis_code', 'pro_code', 'name_en', 'name_kh', 'note', 'active');
    
    /**
     * Get the province
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'pro_code');
    }

    /**
     * Get the communes
     */
    public function communes()
    {
        return $this->hasMany(Commune::class, 'dis_code');
    }


    /**
     * Get only active district
     *
     * Query Scope
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
