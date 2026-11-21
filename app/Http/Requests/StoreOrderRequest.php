<?php

namespace App\Http\Requests;

use App\Enum\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'regex:/^\+?\d[\d .()\-]{6,19}$/'],
            'customer_address' => ['required', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', Rule::in([PaymentMethod::Cod->value, PaymentMethod::BankTransfer->value])],
            'checkout_token' => ['required', 'uuid'],
            'quote_signature' => ['required', 'regex:/^[a-f0-9]{64}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập họ và tên.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.regex' => 'Vui lòng nhập số điện thoại hợp lệ.',
            'customer_address.required' => 'Vui lòng nhập địa chỉ nhận hàng.',
            'payment_method.in' => 'Vui lòng chọn phương thức thanh toán hợp lệ.',
        ];
    }
}
