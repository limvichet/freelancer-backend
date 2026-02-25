<?php

namespace App\Models\CPD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditOption extends Model
{
    use HasFactory;

     protected $table = 'cpd_credit_options';
    protected $primaryKey = 'credit_option_id';

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [];
}
