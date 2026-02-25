<?php

namespace App\Models\Appstore;

use App\Models\Appstore\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $table = 'appstore_categories';

    public function products() {
        return $this->hasMany(Product::class);
    }

}
