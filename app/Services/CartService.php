<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class CartService
{

	public function getCartById($userId)
	{
		$cart = Cart::where('user_id', $userId)
		->with('items.menu')
		->first();
        if ($cart === null) {
            return ['items' => [], 'total' => 0];
        }
        $cartItems = $cart->items->toArray() ?? [];
        $cartTotal = $cart->items->sum('subtotal') ?? 0;

        return ['items' => $cartItems, 'total' => $cartTotal];

    }
	/**
	* Menambahkan item ke keranjang
	*/
	public function addToCart($userId, array $data)
	{
		return DB::transaction(function () use ($userId, $data) {
			$cart = Cart::firstOrCreate(['user_id' => $userId, 'table_id' =>$data->table_id	?? null]);

			$menu = Menu::findOrFail($data['menu_id']);
			$price = $menu->price;

			$cartItem = CartItem::where('cart_id', $cart->id)
			->where('menu_id', $menu->id)
			->first();

			if ($cartItem) {
				$newQuantity = $cartItem->qty + $data['quantity'];
				$cartItem->update([
					'qty' => $newQuantity,
					'subtotal' => $newQuantity * $price,
					'note'    => $data['notes'] ?? $cartItem->notes
				]);
			} else {
				$cartItem = CartItem::create([
					'cart_id'    => $cart->id,
					'menu_id'    => $menu->id,
					'price'      => $price,
					'qty'   		 => $data['quantity'],
					'subtotal'   => $price * $data['quantity'],
					'note'      => $data['notes'] ?? null,
				]);
			}

			return [ 'cart_count' => $cart->items()->count('id')];
		});
	}
    /**
     * Update jumlah item dan hitung ulang total
     */
    public function updateItemQuantity($userId, $cartItemId, $quantity)
    {
        return DB::transaction(function () use ($userId, $cartItemId, $quantity) {
            $cartItem = CartItem::where('id', $cartItemId)
            ->whereHas('cart', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

            $cartItem->qty = $quantity; 
            $cartItem->subtotal = $cartItem->menu->price * $quantity;
            $cartItem->save();

            return [
                'subtotal' => $cartItem->subtotal,
                'cart_total' => $this->recalculateCartTotal($cartItem->cart)
            ];
        });
    }

    /**
     * Update catatan item
     */
    public function updateItemNote($userId, $cartItemId, $note)
    {
        $cartItem = CartItem::where('id', $cartItemId)
        ->whereHas('cart', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->firstOrFail();

        $cartItem->note = $note;
        $cartItem->save();

        return true;
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function deleteItem($userId, $cartItemId)
    {
        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = CartItem::where('id', $cartItemId)
            ->whereHas('cart', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();

            $cart = $cartItem->cart;

            $cartItem->delete();

            return $this->recalculateCartTotal($cart);
        });
    }

    /**
     * Kosongkan keranjang (Hapus semua item)
     */
    public function clearUserCart($userId)
    {
        return DB::transaction(function () use ($userId) {
            $cart = Cart::where('user_id', $userId)
            ->first();

            if ($cart) {
                $cart->items()->delete();
            }
            return true;
        });
    }

    /**
     * Helper: Hitung ulang total belanjaan
     */
    protected function recalculateCartTotal(Cart $cart)
    {
      $total = $cart->items()->sum('subtotal');
      return $total;
  }
}