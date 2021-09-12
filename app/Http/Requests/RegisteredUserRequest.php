<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisteredUserRequest extends FormRequest
{
    /**
     * Determine if the partners is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'regex:/^\+7\s\(\d{3}\)\s\d{3}\-\d{2}\-\d{2}$/', 'unique:users'],
            'telegram_id' => ['string', 'numeric', 'nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле Имя является обязательным',
            'name.max' => 'Поле Имя не должно превышать :max символов',
            'tel.required' => 'Поле Телефон является обязательным',
            'tel.unique' => 'Пользователь с таким номером телефона уже существует',
            'tel.regex' => 'Неверно указан номер телефона. Необходимо писать в формате: +7 (978) 111-11-11',
            'email.required' => 'Поле Email является обязательным',
            'email.email' => 'В поле Email должен быть действительный адрес электронной почты',
            'email.max' => 'Поле Email не должно превышать :max символов',
            'email.unique' => 'Пользователь с таким Email уже существует',
            'password.required' => 'Поле Пароль обязательно для заполнения',
            'password.confirmed' => 'Введенные пароли не совпадают',
            'password.min' => 'Пароль должен содержать не менее :min символов',
        ];
    }
}
