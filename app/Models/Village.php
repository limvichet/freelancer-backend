<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $commune_id
 */
class Village extends Model
{
    protected $table = 'sys_location_villages';
    protected $primaryKey = 'vil_code';

    public $incrementing = false;
    public $timestamps = false;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $fillable = ['vil_code', 'com_code', 'name_en', 'name_kh', 'reference', 'active'];

    public $casts = [
        'vil_code' => 'string',
    ];
    
    /**
     * Get the commune
     */
    public function commune()
    {
        return $this->belongsTo(Commune::class, 'com_code');
    }

}
