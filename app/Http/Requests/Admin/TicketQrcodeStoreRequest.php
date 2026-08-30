<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketQrcodeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_tiket' => [
                'required',
                'string',
                'max:100',
            ],

            'qrcode' => [
                'required',
                'string',
                'max:255',
            ],

            'ticket_type' => [
                'required',
                'string',
                'max:100',
            ],

            'remark' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function sanitized(): array
    {
        return $this->validated();
    }
}
