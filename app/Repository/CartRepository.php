<?php

namespace App\Repository;

use Exception;
use App\Models\Cart;
use App\Models\CartMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartRepository
{
    public Request $request;

    public $cartTotalPrice = 0;

    public $totalPrice = 0;

    public $menuList = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function listingCart()
    {
        $query = Cart::query();

        $result = $query->with('token')
            ->paginate($this->request->limit);

        return response()->json([
            'success' => true,
            'can_load_more' => canLoadMore($result),
            'total' => $result->total(),
            'data' => $result->getCollection(),
        ]);
    }

    public function cartStoreAndUpdate()
    {
        DB::beginTransaction();
        try {
            $cart = Cart::where('token_id', $this->request->token_id)
                ->first();

            if(!$cart) {
                $cart = new Cart();
                $cart->token_id = $this->request->token_id;
                $cart->save();
            }

            $cartMenu = CartMenu::where('menu_id', $this->request->menu_id)
                ->where('cart_id', $cart->id)
                ->first();

            if(!$cartMenu) {
                $newCartMenu = new CartMenu();
                $newCartMenu->cart_id = $cart->id;
                $newCartMenu->menu_id = $this->request->menu_id;
                $newCartMenu->quantity = $this->request->quantity;
                $newCartMenu->save();
            } else {
                $cartMenu->quantity += $this->request->quantity;
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

    /**
     * quantity update
     *
     * @param [type] $id
     * @return void
     */
    public function quantityUpdate($id)
    {
        $cart = Cart::find($id);

        if(!$cart) {
            throw new Exception('Not Found', 404);
        }

        $cartMenu = CartMenu::where('menu_id', $this->request->menu_id)
        ->where('cart_id', $cart->id)
        ->first();

        if(!$cartMenu) {
            throw new Exception('Not Found', 404);
        }

        $cartMenu->quantity = $this->request->quantity;
        $cartMenu->update();

        return response()->json([
            'success' => true,
            'message' => 'Successfully update cart'
        ]);
    }

    /**
     * detail Cart
     *
     * @param [type] $id
     * @return void
     */
    public function detailCart($id)
    {
        $cart = Cart::with(['token',
        'cartMenus',
        'cartMenus.menu'])
            ->where('id', $id)
            ->first();

        if(!$cart) {
            throw new Exception('Cart Not Found', 404);
        }

        foreach($cart->cartMenus as $menu) {
            $total = $menu->menu->price * $menu->quantity;
            $this->totalPrice = $total;
            $this->cartTotalPrice += $total;
            $this->menuList[] = [
                'id' => $menu->menu->id,
                'name' => $menu->menu->name,
                'price' => $menu->menu->price,
                'quantity' => $menu->quantity,
                'total_price' => $this->totalPrice,
            ];
        }

        $list = [
            'cart_id' => $cart->id,
            'token_id' => $cart->token_id,
            'token_number' => $cart->token->number,
            'menus' => $this->menuList,
        ];

        return response()->json([
            'success' => true,
            'data' => $list,
        ]);
    }
}
