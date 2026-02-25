<?php

namespace App\Http\Controllers\Api\VAppstore;

use App\Http\Controllers\Controller;
use App\Models\Appstore\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $param = $request->query('param');

        return Product::with('category')
            ->when($param, function ($query) use ($param) {
                $query->where('name', 'like', "%{$param}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    public function store(Request $request)
    {
        return Product::create($request->all());
    }

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $p = Product::findOrFail($id);
        $p->update($request->all());
        return $p;
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
