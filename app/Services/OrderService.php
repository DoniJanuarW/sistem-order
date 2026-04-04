<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class OrderService
{
    /**
     * Ambil semua order hari ini (dashboard)
     */
    public function all()
    {
        return Order::with(['items.menu', 'table', 'customer'])
        ->whereDate('created_at', now())
        ->latest()
        ->get();
    }

    /**
     * Ambil order berdasarkan status
     */
    public function byStatus(string $status)
    {
        return Order::with(['items.menu', 'table'])
        ->where('status', $status)
        ->latest()
        ->get();
    }

    /**
     * Detail order
     */
    public function find(int $id): Order
    {
        return Order::with(['customer','items.menu', 'table','payment'])
        ->findOrFail($id);
    }

    /**
     * Buat order baru (Kasir / Customer)
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();
            $isCustomer = $user && $user->role === 'customer'; 

            // PREPARE DATA ROLE CUSTOMER
            if ($isCustomer) {
                $cart = Cart::where('user_id', $user->id)
                ->with('items.menu')
                ->first();

                if (!$cart || $cart->items->isEmpty()) {
                    throw new Exception("Keranjang belanja kosong.");
                }

                $rawItems = $cart->items->map(function($item) {
                    return [
                        'menu_id' => $item->menu_id,
                        'quantity'=> $item->qty,
                        'price'   => $item->price,
                        'note'    => $item->note
                    ];
                })->toArray();

                $customerName = null;
                $customerId   = $user->id;
                
                $calculatedTotal = $cart->total_price; 

            } else {

                $rawItems = $data['items'];
                $customerName = $data['customer_name'] ?? 'Guest';
                $customerId   = null;
                $calculatedTotal = $data['total_amount'];
            }

            [$finalTotal, $formattedItems] = $this->prepareOrderItems($rawItems);

            if (!$isCustomer) {
                $this->validateTotal($finalTotal, $calculatedTotal);
            }

            $table = $data['table_number'] != null ? $this->resolveTable($data['table_number']) : null;

            $order = Order::create([
                'table_id'     => $table ? $table->id : null,
                'customer_id'  => $customerId,
                'guest_name'   => $customerName,
                'order_code'   => $this->generateOrderCode(),
                'status'       => 'pending',
                'total_amount' => $finalTotal,
            ]);

            $order->items()->createMany($formattedItems);

            if ($isCustomer) {
                $cart->items()->delete();
                $cart->delete();
                
                if (isset($data['payment_method'])) {
                    $payMethod = $data['payment_method'] === 'online' ? 'midtrans' : 'cash';
                    $this->createPayment($order, $finalTotal, $payMethod, 'pending');
                }

            } else {
                if (isset($data['payment_method'])) {
                    $this->createPayment($order, $finalTotal, $data['payment_method']);
                }
            }

            return $order;
        });
    }

    /**
     * Update status order
     */
    public function updateStatus(int $id, string $status): Order
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);

        return $order;
    }

    /**
     * Batalkan order
     */
    public function cancel(int $id, ?string $reason = null): Order
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => 'cancelled',
            'cancel_reason' => $reason
        ]);

        return $order;
    }


    protected function prepareOrderItems(array $items)
    {
        $total = 0;
        $result = [];

        foreach ($items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);

            $subtotal = $menu->price * $item['quantity'];
            $total += $subtotal;

            $result[] = [
                'menu_id' => $menu->id,
                'qty' => $item['quantity'],
                'price_at_transaction' => $menu->price,
                'subtotal' => $subtotal,
                'note' => $item['note'] ?? null,
            ];
        }

        return [$total, $result];
    }

    protected function validateTotal(float $backendTotal, float $frontendTotal): void
    {
        if (abs($backendTotal - $frontendTotal) > 100) {
            throw new Exception("Total tidak valid.");
        }
    }

    protected function resolveTable(string|int $tableNo): Table
    {
        $table = Table::where('table_number', $tableNo)->first();

        if (!$table) {
            throw new Exception("Meja {$tableNo} tidak ditemukan.");
        }

        return $table;
    }

    protected function createPayment(Order $order, float $amount, string $method, ?string $stats = null): Payment
    {
        $status = $stats ?? match ($method) {
            'transfer', 'qris', 'midtrans' => 'pending', 
            default => 'success',            
        };
        return Payment::create([
            'payment_code' => $this->generatePaymentCode(),
            'order_id' => $order->id,
            'method' => $method,
            'amount' => $amount,
            'payment_status' => $status,
            'paid_at' => $status === 'success' ? now() : null,
        ]);
    }

    protected function generateOrderCode(): string
    {
        $last = Order::lockForUpdate()->latest('id')->value('order_code');

        $number = $last ? ((int) substr($last, 3)) + 1 : 1;

        return 'ORD' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    protected function generatePaymentCode(): string
    {
        do {
            $code = 'TRX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4));
        } while (Payment::where('payment_code', $code)->exists());

        return $code;
    }

    public function getCustomerOrders()
    {
        $query = Order::with(['items.menu', 'table', 'payment'])
            ->where('customer_id', Auth::id())
            ->latest();

        $statusFilter = request('status', 'semua');
        $tipeFilter   = request('tipe', 'semua');

        if ($statusFilter !== 'semua') {
            if ($statusFilter === 'pending') {
                // "Belum Bayar": Order belum selesai/batal, dan (belum ada data payment ATAU payment masih pending)
                $query->whereNotIn('status', ['cancelled', 'completed'])
                    ->where(function ($q) {
                        $q->doesntHave('payment')
                            ->orWhereHas('payment', function ($subQ) {
                                $subQ->whereIn('payment_status', ['pending', 'unpaid']);
                            });
                    });
            } 
            elseif ($statusFilter === 'proses') {
                // "Diproses": Order belum selesai/batal, dan payment sudah berhasil dibayar
                $query->whereNotIn('status', ['cancelled', 'completed'])
                    ->whereHas('payment', function ($subQ) {
                        $subQ->whereIn('payment_status', ['settlement', 'capture', 'success']);
                    });
            } 
            else {
                $query->where('status', $statusFilter);
            }
        }

        // Terapkan Filter Tipe Pesanan (Dine-in / Takeaway)
        if ($tipeFilter !== 'semua') {
            if ($tipeFilter === 'dine_in') {
                $query->whereNotNull('table_id');
            } else {
                $query->whereNull('table_id');
            }
        }

        // 5. Eksekusi Query setelah semua filter diterapkan
        $orders = $query->get();

        // 6. Return beserta fungsi pengurutan (Sorting) kustom milik Anda
        return $orders->sortBy(function ($order) {
            
            if ($order->status == 'cancelled') return 5;

            $paymentStatus = $order->payment ? $order->payment->payment_status : 'unpaid';

            if ($paymentStatus == 'pending' || $paymentStatus == 'unpaid') return 1;

            if ($order->status == 'pending') return 2;
            if ($order->status == 'cooking' || $order->status == 'process') return 3;
            if ($order->status == 'completed') return 4;

            return 6;

        })->values();
    }

    public function getAdminOrdersQuery($request)
    {
        $query = Order::with(['payment', 'table', 'items.menu', 'customer'])
        ->latest();

        // Filter Tanggal (Range)
        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Filter Status Order
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Pencarian (Kode Order / Nama Customer / Nama Guest)
        if ($request->search) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('order_code', 'like', "%{$keyword}%")
                ->orWhere('guest_name', 'like', "%{$keyword}%")
                ->orWhereHas('customer', function($c) use ($keyword) {
                  $c->where('name', 'like', "%{$keyword}%");
              });
            });
        }

        return $query;
    }

    public function generateSnapToken(Order $order): string
    {
        // 1. Setup Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $user = Auth::user();

        $item_details = [];
        foreach ($order->items as $item) {
            $item_details[] = [
                'id'       => $item->menu_id,
                'price'    => (int) $item->menu->price * $item->qty,
                'quantity' => $item->qty,
                // Batasi nama item maksimal 50 karakter (aturan Midtrans)
                'name'     => substr($item->menu->name ?? 'Menu', 0, 50) 
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_code . '-' . time(), 
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user ? $user->name : ($order->guest_name ?? 'Guest'),
                'email'      => $user ? $user->email : 'guest@grandsanthi.com',
                'phone'      => $user ? $user->phone : '080000000000',
            ],
            'item_details' => $item_details
        ];

        try {
            // 4. Minta Token ke Server Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 5. Simpan token ke database order
            $order->payment()->update(['snap_token' => $snapToken]);

            return $snapToken;

        } catch (Exception $e) {
            throw new Exception("Gagal terhubung ke server pembayaran: " . $e->getMessage());
        }
    }

}
