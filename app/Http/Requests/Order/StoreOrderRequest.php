<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'customer_name'   => 'required|string|max:255',
            'order_type'       => 'requiwred|in:dine_in,takeaway',
            'table_number'     => 'nullable|required_if:order_type,dine_in',
            'payment_method'  => 'required|in:cash,qris,transfer',
            'total_amount'    => 'required|numeric|min:0',
            'items'           => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.note'     => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Minimal harus ada satu item pesanan.',
            'items.*.menu_id.exists' => 'Menu tidak ditemukan.',
            'items.*.quantity.min' => 'Jumlah minimal 1.',
        ];
    }
}
