<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return isLogged();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text'       => ['required', 'string'],
            'name'       => ['required', 'string', 'max:255']
        ];
    }

    public function attributes(): array
    {
        return [
            'text' => 'текст отзыва',
            'name' => 'имя'
        ];
    }

    protected function getRedirectUrl()
    {
        $receipt = $this->route('receipt');
        return "/receipt/{$receipt->id}#comment";
    }
}
