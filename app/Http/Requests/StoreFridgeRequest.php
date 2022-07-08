<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFridgeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'throw_it_out_treshold' => [
                'integer',
                'nullable'
            ],
            'asap_treshold' => [
                'gt:throw_it_out_treshold',
                'integer',
                'nullable'
            ],
            'in_near_future_treshold' => [
                'gt:asap_treshold',
                'integer',
                'nullable'
            ]
        ];
    }
}
