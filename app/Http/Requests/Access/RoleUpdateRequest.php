<?php

// app/Http/Requests/Access/RoleUpdateRequest.php
namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }
    public function rules(): array
    {
        $id = $this->route('role')->id;
        return ['name' => "required|string|alpha_dash:ascii|unique:roles,name,{$id}"];
    }
}
