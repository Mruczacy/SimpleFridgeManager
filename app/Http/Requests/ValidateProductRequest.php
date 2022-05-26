<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'product_category_id' => 'required|numeric|exists:product_categories,id',
            'fridge_id' => 'required|numeric|exists:fridges,id',
            'expiration_date' => 'required|date',
        ];
    }
}
