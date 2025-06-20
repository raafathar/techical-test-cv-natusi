<?php

namespace App\Http\Requests\Dashboard\Distributor;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistributorRequest extends FormRequest
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
            'nama'      => 'required|string|max:255',
            'alamat'    => 'required|string|max:255',
            'telepon'   => 'required|string|max:20',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }
}
