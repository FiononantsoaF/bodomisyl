<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarteCadeauRequest extends FormRequest
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
			'code' => 'required|string',
			'beneficiaire' => 'required|string',
			'contact' => 'required|string',
			'client_id' => 'required',
			'service_id' => 'required',
			'montant' => 'required',
			'date_emission' => 'required',
			'is_active' => 'required',
        ];
    }
}
