<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarteCadeauClientRequest extends FormRequest
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
			'benef_name' => 'required|string',
			'carte_cadeau_service_id' => 'required',
			'benef_contact' => 'required|string',
			'client_id' => 'required',
			'amount' => 'required',
			'start_date' => 'required',
			'is_active' => 'required',
        ];
    }
}
