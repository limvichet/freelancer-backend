<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model
{
     protected $table = 'portfolio_articles';

    protected $fillable = [
        'title',
        'category_id',
        'date',
        'excerpt',
        'publication',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getDateAttribute($value) {
        if (!$value) return null;
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setDateAttribute($value) {
        if (!$value) {
            $this->attributes['date'] = null;
            return;
        }

        try {
            $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            $this->attributes['date'] = null;
        }
    }

}
