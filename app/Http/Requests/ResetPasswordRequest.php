<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    public function messages()
    {
        return [
            'token.required' => 'Не заполнено поле с секретным ключем, которое заполняется автоматически. Обратитесь к администратору',
            'email.required' => 'Поле Email является обязательным',
            'email.email' => 'В поле Email должен быть действительный адрес электронной почты',
            'email.max' => 'Поле Email не должно превышать :max символов',
            'email.exists' => 'Пользователь с таким Email не зарегистрирован',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.confirmed' => 'Введенные пароли не совпадают',
            'password.min' => 'Пароль должен содержать не менее :min символов',
        ];
    }
}
