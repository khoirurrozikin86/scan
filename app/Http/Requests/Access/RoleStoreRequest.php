<?php

// app/Http/Requests/Access/RoleStoreRequest.php
namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }
    public function rules(): array
    {
        return ['name' => 'required|string|alpha_dash:ascii|unique:roles,name'];
    }
}
