<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScanTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'outlet_id' => [
                'required',
                'integer',
                'exists:outlets,id',
            ],

            'qrcode' => [
                'required',
                'string',
                'max:255',
            ],

            'scan_method' => [
                'required',
                Rule::in([
                    'camera',
                    'scanner',
                ]),
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
        return [
            'outlet_id' => $this->integer('outlet_id'),

            'qrcode' => trim(
                $this->input('qrcode')
            ),

            'scan_method' => $this->input(
                'scan_method'
            ),

            'remark' => $this->input('remark'),
        ];
    }
}
