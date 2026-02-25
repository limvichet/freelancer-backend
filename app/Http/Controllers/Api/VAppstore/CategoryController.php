<?php

namespace App\Http\Controllers\Api\VAppstore;

use App\Http\Controllers\Controller;
use App\Models\Appstore\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::all();
    }
}
