<?php

namespace App\Http\Controllers\Api;

use App\Models\Token;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    /**
     * token listing
     *
     * @return void
     */
    public function listing(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
            'limit' => 'required|numeric'
        ]);

        $query = Token::query();

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
