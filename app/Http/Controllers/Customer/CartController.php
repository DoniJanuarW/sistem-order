<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    protected $cartService;

    // Inject Service ke Constructor
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    public function index()
    {   
        $userId = Auth::id();
        $cart = $this->cartService->getCartById($userId);
        $cartItems = $cart['items']; 
        $cartTotal = $cart['total'];
        return view('customer.cart', compact('cartItems', 'cartTotal'));

    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:255',
            'table_id'   => 'nullable|exists:tables,table_number',
        ]);

        try {
            $userId = Auth::id();

            if (!$userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu.'
                ], 401);
            }

            $result = $this->cartService->addToCart($userId, $request->only(['menu_id', 'quantity', 'notes', 'table_id']));

            return response()->json([
                'status'  => 'success',
                'message' => 'Berhasil ditambahkan ke keranjang!',
                'data'    => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Quantity (POST /cart/update-qty/{id})
     */
    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);
        try {
            $result = $this->cartService->updateItemQuantity(Auth::id(), $id, $request->qty);

            return response()->json([
                'status' => 'success',
                'message' => 'Jumlah diupdate',
                'subtotal' => $result['subtotal'],
                'cart_total' => $result['cart_total']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Item tidak ditemukan atau error server'], 500);
        }
    }

    /**
     * Update Note (POST /cart/update-note/{id})
     */
    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:255',
        ]);

        try {
            $this->cartService->updateItemNote(Auth::id(), $id, $request->note);

            return response()->json([
                'status' => 'success',
                'message' => 'Catatan disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan catatan'], 500);
        }
    }

    /**
     * Hapus Item (DELETE /cart/{id})
     */
    public function destroy($id)
    {
        try {
            $newTotal = $this->cartService->deleteItem(Auth::id(), $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Item dihapus dari keranjang',
                'cart_total' => $newTotal
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus item'], 500);
        }
    }

    /**
     * Kosongkan Keranjang (DELETE /cart/clear)
     */
    public function clearCart()
    {
        try {
            $this->cartService->clearUserCart(Auth::id());

            return response()->json([
                'status' => 'success',
                'message' => 'Keranjang dikosongkan'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal mengosongkan keranjang'], 500);
        }
    }
}