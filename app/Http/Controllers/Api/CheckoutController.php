<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InvoiceMenu;

class CheckoutController extends Controller
{
    public $totalPrice;

    public $insertInvoiceMenu;

    public function checkout($id)
    {
        DB::beginTransaction();
        try {
            $cart = Cart::with(['token', 'cartMenus', 'cartMenus.menu'])->find($id);

            $invoice = new Invoice();
            $invoice->invoice_number = 'INV-'.  rand(0000000, 9999999);
            $invoice->token_id = $cart->token_id;
            $invoice->save();

            foreach($cart->cartMenus as $menu) {
                $total = $menu->menu->price * $menu->quantity;
                $this->insertInvoiceMenu[] = [
                    'invoice_id' => $invoice->id,
                    'menu_id' => $menu->menu_id,
                    'quantity' => $menu->quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $this->totalPrice += $total;
            }

            InvoiceMenu::insert($this->insertInvoiceMenu);

            $invoice->grand_total = $this->totalPrice;
            $invoice->update();


            // delete cart
            $cart->delete();
            $cart->cartMenus()->delete();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'successfully created',
            ]);
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
