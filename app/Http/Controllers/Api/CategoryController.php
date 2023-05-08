<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * categories listing
     *
     * @param Request $request
     * @return void
     */
    public function listing(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
            'limit' => 'required|numeric'
        ]);

        $query = Category::query();

        $result = $query->orderBy('id', 'asc')
            ->paginate($request->limit);

        return response()->json([
            'success' => true,
            'can_load_more' => canLoadMore($result),
            'total'         => $result->total(),
            'data'          => $result->getCollection(),
        ]);
    }
}
