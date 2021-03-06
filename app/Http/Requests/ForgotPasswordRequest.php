<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|exists:users',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле Email является обязательным',
            'email.email' => 'В поле Email должен быть действительный адрес электронной почты',
            'email.max' => 'Поле Email не должно превышать :max символов',
            'email.exists' => 'Пользователя с таким Email не найдено',
        ];
    }
}
