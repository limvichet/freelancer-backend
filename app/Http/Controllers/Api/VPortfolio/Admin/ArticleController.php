<?php

namespace App\Http\Controllers\Api\VPortfolio\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio\Article;


class ArticleController extends Controller
{
    // GET /api/articles
    public function index(Request $request)
    {
        $param = $request->query('param');

        return Article::with('category')
            ->when($param, function ($query) use ($param) {
                $query->where('title', 'like', "%{$param}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(5);
    }

    // POST /api/articles
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'category_id' => 'required',
            'date' => 'required|date',
            'excerpt' => 'required',
            'publication' => 'required',
        ]);

        $article = Article::create($data);

        return response()->json($article, 201);
    }

    // GET /api/articles/{id}
    public function show(string $id)
    {
        return response()->json(
            Article::findOrFail($id)
        );
    }

    // PUT /api/articles/{id}
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $article->update($request->all());

        return response()->json($article);
    }

    // DELETE /api/articles/{id}
    public function destroy(string $id)
    {
        Article::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
