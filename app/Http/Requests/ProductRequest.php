<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Fridge;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isFridgeUser(Fridge::findOrFail($this->fridge_id)) || $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|numeric|exists:product_categories,id',
            'fridge_id' => 'required|numeric|exists:fridges,id',
            'expiration_date' => 'required|date',
        ];
    }
}
