<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'slug' => 'string|max:255|unique:products,slug,' . $this->product,
            'description' => 'nullable|string',
            'price' => 'numeric',
        ];
    }
}
