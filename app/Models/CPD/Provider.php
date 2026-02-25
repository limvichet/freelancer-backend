<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'cpd_providers';
    protected $primaryKey = 'provider_id';

    protected $guarded = [];
    protected $casts = [];

    // Provider type
    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id');
    }

    // Provider category
    public function providerCategory()
    {
        return $this->belongsTo(ProviderCategory::class, 'provider_cat_id');
    }
    // Accreditation
    public function accreditation()
    {
        return $this->belongsTo(Accreditation::class, 'accreditation_id');
    }
    // Address info
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

    // CPD Courses/Activities
    public function CPDCourses()
    {
        return $this->belongsToMany(Provider::class, 'cpd_course_providers', 'provider_id', 'cpd_course_id');
    }

    public function providerUser()
    {
        return $this->belongsTo('App\Models\User', 'provider_id');
    }
}
