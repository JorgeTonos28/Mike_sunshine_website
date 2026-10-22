<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|uuid', 'customer_name' => 'required|string|max:160',
            'customer_email' => 'required|email|max:255', 'customer_phone' => 'nullable|string|max:50',
            'delivery_method' => 'required|in:shipping,collection',
            'shipping_address' => 'nullable|required_if:delivery_method,shipping|string|max:1500',
            'message' => 'nullable|string|max:3000', 'privacy' => 'required|accepted', 'terms' => 'required|accepted',
            'website' => 'nullable|prohibited',
        ];
    }
}
