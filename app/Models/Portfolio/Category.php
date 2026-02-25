<?php

namespace App\Models\Portfolio;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $table = 'portfolio_categories';

    /**
     * Get the articles for the category.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

}
