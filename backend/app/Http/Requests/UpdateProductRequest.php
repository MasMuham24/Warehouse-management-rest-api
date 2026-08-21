<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'supplier_id' => ['sometimes', 'required', 'exists:suppliers,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],

            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($this->product),
            ],

            'description' => ['sometimes', 'nullable', 'string'],

            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],

            'stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}
