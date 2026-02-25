<?php

namespace App\Http\Controllers\Api\VPortfolio\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }
}
