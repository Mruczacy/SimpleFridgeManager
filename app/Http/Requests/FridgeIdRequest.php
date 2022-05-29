<?php

namespace App\Http\Requests;

use App\Models\Fridge;
use Illuminate\Foundation\Http\FormRequest;

class FridgeIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return $this->user()->isFridgeUser(Fridge::findOrFail($this->fridge_id)) || $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fridge_id' => 'required|numeric|exists:fridges,id',
        ];
    }
}
