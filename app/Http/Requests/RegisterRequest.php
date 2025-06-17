<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'regex:/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'max:255'],
            'name'     => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => 'почта',
            'password' => 'пароль'
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Введите корректный email с доменом, например example@mail.ru.'
        ];
    }
}
