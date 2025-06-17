<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiptStoreRequest extends FormRequest
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
            'title'        => ['required', 'string', 'max:255'],
            'period_id'    => ['required', 'exists:periods,id'],
            'category_id'  => ['required', 'exists:categories,id'],
            'country_code' => ['required', 'string', 'max:2'],
            'cuisine_id'   => ['required', 'exists:cuisines,id'],
            'image'        => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'description'  => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'latitude'     => ['required', 'numeric'],
            'longitude'    => ['required', 'numeric'],
            'ingredients'  => ['required'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title'         => 'название',
            'description'   => 'описание',
            'ingredients'   => 'ингредиенты',
            'instructions'  => 'инструкции',
            'latitude'      => 'широта',
            'longitude'     => 'длина',
            'period_id'     => 'период',
            'image'         => 'изображение',
            'calories'      => 'калории',
            'squirrels'     => 'белки',
            'fats'          => 'жиры',
            'carbohydrates' => 'углеводы',
        ];
    }
}
