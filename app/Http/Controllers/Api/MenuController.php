<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * menu listing
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function listing($categoryId, Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
            'limit' => 'required|numeric'
        ]);

        $query = Menu::query();

        $result = $query
            ->with('category')
            ->where('category_id', $categoryId)
            ->orderBy('id', 'asc')
            ->paginate($request->limit);

        return response()->json([
            'success' => true,
            'can_load_more' => canLoadMore($result),
            'total'         => $result->total(),
            'data'          => $result->getCollection(),
        ]);
    }
}
