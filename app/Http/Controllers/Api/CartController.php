<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CartMenu;
use App\Repository\CartRepository;

class CartController extends Controller
{
    /**
     * cart Token Listing
     *
     * @param Request $request
     * @return void
     */
    public function cartTokenListing(Request $request)
    {
        $request->validate([
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        $cartRepo = new CartRepository($request);
        return $cartRepo->listingCart();
    }

    /**
     * store carts
     *
     * @param Request $request
     * @return void
     */
    public function storeAndUpdate(Request $request)
    {
        $request->validate([
            'token_id' => 'required',
            'menu_id' => 'required',
        ]);

        $cartRepo = new CartRepository($request);
        return $cartRepo->cartStoreAndUpdate();
    }

    /**
     * cart detail
     *
     * @param [type] $id
     * @return void
     */
    public function detail(Request $request, $id)
    {
        $cartRepo = new CartRepository($request);
        return $cartRepo->detailCart($id);
    }
}
