<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('user');
        return [
            'name' => ['required','string','max:150'],
            'email' => ['required','email','max:150', Rule::unique('users','email')->ignore($id)],
            'password' => ['nullable','min:6'],
            'roles' => ['array'],
            'roles.*' => ['string'],
            'active' => ['nullable','boolean'],
        ];
    }

    public function sanitized(): array
    {
        $v = $this->validated();
        $v['active'] = (bool)($v['active'] ?? true);
        return $v;
    }
}
