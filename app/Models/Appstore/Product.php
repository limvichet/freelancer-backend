<?php

namespace App\Models\Appstore;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'appstore_products';

    protected $fillable = [
        'name',
    	'category_id'
    ];
    public function category() {
        return $this->belongsTo(Category::class);
    }

}
