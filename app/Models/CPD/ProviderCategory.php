<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCategory extends Model
{
    use HasFactory;

    protected $table = 'cpd_provider_categories';
    protected $primaryKey = 'provider_cat_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
