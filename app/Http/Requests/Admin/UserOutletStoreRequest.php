<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserOutletStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'outlet_ids' => [
                'nullable',
                'array',
            ],

            'outlet_ids.*' => [
                'integer',
                'exists:outlets,id',
            ],
        ];
    }

    public function sanitized(): array
    {
        return [
            'user_id' => $this->integer('user_id'),

            'outlet_ids' => array_map(
                'intval',
                $this->input('outlet_ids', [])
            ),
        ];
    }
}
