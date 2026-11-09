<?php

namespace App\Http\Requests;

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
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk tidak boleh melebihi 100 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka nominal.',
            'price.min' => 'Harga produk tidak boleh bernilai negatif.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa bilangan bulat.',
            'stock.min' => 'Stok produk tidak boleh kurang dari 0.',
        ];
    }
}
