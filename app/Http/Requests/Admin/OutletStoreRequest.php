<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OutletStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'outlet_code' => [
                'required',
                'string',
                'max:50',
                'unique:outlets,outlet_code',
            ],

            'outlet_name' => [
                'required',
                'string',
                'max:150',
            ],

            'outlet_type' => [
                'required',
                'string',
                'max:50',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'is_camera_enabled' => [
                'nullable',
                'boolean',
            ],

            'is_scanner_enabled' => [
                'nullable',
                'boolean',
            ],

            'remark' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ];
    }

    public function sanitized(): array
    {


        return $this->validated();
    }
}
