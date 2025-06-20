<?php

namespace App\Http\Requests\Dashboard\Drug;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDrugRequest extends FormRequest
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
            'kode_obat'    => 'required|string|max:45',
            'nama_obat'    => 'required|string|max:255',
            'satuan_obat'  => 'required|string|max:45',
            'harga_obat'   => 'required|numeric|min:0',
            'stock_obat'   => 'required|integer|min:0',
        ];
    }
}

