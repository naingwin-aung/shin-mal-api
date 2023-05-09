<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CartMenu;

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

        $query = Cart::query();

        $result = $query->with('token')
            ->orderBy('id', 'asc')
            ->paginate($request->limit);

        return response()->json([
            'success' => true,
            'can_load_more' => canLoadMore($result),
            'total' => $result->total(),
            'data' => $result->getCollection(),
        ]);
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

        DB::beginTransaction();
        try {
            $cart = Cart::where('token_id', $request->token_id)
                ->first();

            if(!$cart) {
                $cart = new Cart();
                $cart->token_id = $request->token_id;
                $cart->save();
            }

            $cartMenu = CartMenu::where('menu_id', $request->menu_id)
                ->where('cart_id', $cart->id)
                ->first();

            if(!$cartMenu) {
                $newCartMenu = new CartMenu();
                $newCartMenu->cart_id = $cart->id;
                $newCartMenu->menu_id = $request->menu_id;
                $newCartMenu->quantity = 1;
                $newCartMenu->save();
            } else {
                $cartMenu->quantity += 1;
                $cartMenu->update();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Successfully create cart'
            ]);
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
