<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'number' => 'required|string',
            'order_key' => 'required|string',
            'status' => 'required|string',
            'currency' => 'required|string',
            'date_created' => 'required|date',
            'date_modified' => 'required|date',
            'discount_total' => 'required|numeric',
            'discount_tax' => 'required|numeric',
            'shipping_total' => 'required|numeric',
            'shipping_tax' => 'required|numeric',
            'cart_tax' => 'required|numeric',
            'total' => 'required|numeric',
            'billing' => 'required|array',
            'billing.first_name' => 'required|string',
            'billing.last_name' => 'required|string',
            'billing.company' => 'nullable|string',
            'billing.address_1' => 'required|string',
            'billing.address_2' => 'nullable|string',
            'billing.city' => 'required|string',
            'billing.state' => 'required|string',
            'billing.postcode' => 'required|string',
            'billing.country' => 'required|string',
            'billing.email' => 'required|email',
            'billing.phone' => 'required|string',
            'shipping' => 'required|array',
            'shipping.first_name' => 'required|string',
            'shipping.last_name' => 'required|string',
            'shipping.company' => 'nullable|string',
            'shipping.address_1' => 'required|string',
            'shipping.address_2' => 'nullable|string',
            'shipping.city' => 'required|string',
            'shipping.state' => 'required|string',
            'shipping.postcode' => 'required|string',
            'shipping.country' => 'required|string',
            'payment_method' => 'required|string',
            'payment_method_title' => 'required|string',
            'transaction_id' => 'nullable|string',
            'date_paid' => 'nullable|date',
            'date_paid_gmt' => 'nullable|date',
            'date_completed' => 'nullable|date',
            'date_completed_gmt' => 'nullable|date',
            'cart_hash' => 'nullable|string',
            'line_items' => 'required|array',
            'line_items.*.id' => 'required|integer',
            'line_items.*.name' => 'required|string',
            'line_items.*.product_id' => 'required|integer',
            'line_items.*.variation_id' => 'required|integer',
            'line_items.*.quantity' => 'required|integer',
            'line_items.*.tax_class' => 'nullable|string',
            'line_items.*.subtotal' => 'required|numeric',
            'line_items.*.subtotal_tax' => 'required|numeric',
            'line_items.*.total' => 'required|numeric',
            'line_items.*.total_tax' => 'required|numeric',
            'line_items.*.taxes' => 'required|array',
            'line_items.*.taxes.*.id' => 'required|integer',
            'line_items.*.taxes.*.total' => 'required|numeric',
            'line_items.*.taxes.*.subtotal' => 'required|numeric',
            'line_items.*.meta_data' => 'nullable|array',
            'line_items.*.sku' => 'nullable|string',
            'line_items.*.price' => 'required|numeric',
            'shipping_lines' => 'required|array',
            'shipping_lines.*.id' => 'required|integer',
            'shipping_lines.*.method_title' => 'required|string',
            'shipping_lines.*.method_id' => 'required|string',
            'shipping_lines.*.total' => 'required|numeric',
            'shipping_lines.*.total_tax' => 'required|numeric',
            'shipping_lines.*.taxes' => 'nullable|array',
            'shipping_lines.*.meta_data' => 'nullable|array',
            
        ];
    }
}