<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'assigned_user_id' => [
                'nullable',
                'exists:users,id',
            ],

            'priority' => [
                'required',
                'in:Low,Medium,High',
            ],

            'status' => [
                'required',
                'in:Open,In Progress,Resolved,Closed',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'notes' => [
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