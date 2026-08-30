<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:150'],
            'email' => ['required','email','max:150','unique:users,email'],
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
