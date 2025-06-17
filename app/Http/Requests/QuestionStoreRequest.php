<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
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
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'max:255'],
            'checkbox' => ['required', 'string', 'in:on']
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'имя',
            'phone'    => 'телефон',
            'checkbox' => 'пользовательское соглашение'
        ];
    }

    protected function getRedirectUrl()
    {
        return "/#form";
    }
}
