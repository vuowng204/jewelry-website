<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    public function rules(): array
    {
        return ['quantity' => ['required', 'integer', 'min:1', 'max:2147483647']];
    }

    public function attributes(): array
    {
        return ['quantity' => 'số lượng'];
    }
}
