<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRankCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->isFridgeOwner($this->fridge);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_manager' => 'required|numeric|min:0|max:1',
            'user_id' => 'required|numeric|exists:users,id'
        ];
    }
}
