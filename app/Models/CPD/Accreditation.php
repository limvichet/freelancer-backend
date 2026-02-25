<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;

    protected $table = 'cpd_moeys_accreditations';
    protected $primaryKey = 'accreditation_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
