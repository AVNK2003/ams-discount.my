<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'org' => 'required|string|max:64',
            'title' => 'required|string|max:255',
            'user_id' => 'required|numeric|exists:users,id',
            'priority' => 'nullable|numeric',
            'date_end' => 'nullable|date',
            'categories' => 'required|array',
            'categories.*' => 'required|numeric|exists:categories,id',
            'cities' => 'required|array',
            'cities.*' => 'required|numeric',
            'address' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:255',
            'site' => 'nullable|string|url|max:255',
            'instagram' => 'nullable|regex:/https:\/\/(www.)?instagram.com\/[\D\d]*/',
            'vk' => 'nullable|regex:/https:\/\/vk.com\/[\D\d]*/',
            'facebook' => 'nullable|regex:/https:\/\/(www.)?facebook.com\/[\D\d]*/',
            'youtube' => 'nullable|regex:/https:\/\/(www.)?youtube.com\/[\D\d]*/',
            'discount' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'img' => 'nullable|image|max:1024',
            'agree' => 'required|boolean',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'org.required' => 'Поле "Форма организации" является обязательным',
            'org.string' => 'Поле "Форма организации" должно быть строкой',
            'org.max' => 'Поле "Форма организации" не должно превышать :max символов',
            'title.required' => 'Поле "Название организации" является обязательным',
            'title.string' => 'Поле "Название организации" должно быть строкой',
            'title.max' => 'Поле "Название организации" не должно превышать :max символов',
            'user_id.required' => 'Поле "Владелец организации" является обязательным',
            'user_id.numeric' => 'Поле "Владелец организации" должно содержать уникальный ID',
            'user_id.exists' => 'Пользователя с таким ID не существует',
            'priority.numeric' => 'Поле "Приоритет в каталоге" должно быть числом',
            'date_end.date' => 'Поле "Дата окончания" должно быть датой',
            'categories.required' => 'Вы забыли указать категорию',
            'categories.numeric' => 'Такой категории нет в базе',
            'categories.*.exists' => 'Такой категории нет в базе',
            'cities.required' => 'Вы забыли указать город',
            'cities.numeric' => 'Такого города нет в базе',
            'cities.*.exists' => 'Такого города нет в базе',
            'address.string' => 'Поле "Адрес организации" должно быть строкой',
            'address.max' => 'Поле "Адрес организации" не должно превышать :max символов',
            'working_hours.string' => 'Поле "График работы" должно быть строкой',
            'working_hours.max' => 'Поле "График работы" не должно превышать :max символов',
            'tel.string' => 'Поле "Телефон" должно быть строкой',
            'tel.max' => 'Поле "Телефон" не должно превышать :max символов',
            'site.string' => 'Поле "Сайт" должно быть строкой',
            'site.url' => 'Поле "Сайт" должно содержать полный адрес сайта. https://example.com',
            'site.max' => 'Поле "Сайт" не должно превышать :max символов',
            'instagram.regex' => 'Поле "Instagram" должно содержать ссылку на профиль в сервисе. https://www.instagram.com/***',
            'vk.regex' => 'Поле "VK" должно содержать ссылку на страницу в сервисе. https://vk.com/***',
            'facebook.regex' => 'Поле "Facebook" должно содержать ссылку на профиль в сервисе. https://www.facebook.com/***',
            'youtube.regex' => 'Поле "YouTube" должно содержать ссылку на сервисе YouTube. https://www.youtube.com/***',
            'discount.required' => 'Поле "Размер скидки" является обязательным',
            'discount.string' => 'Поле "Размер скидки" должно быть строкой',
            'discount.max' => 'Поле "Размер скидки" не должно превышать :max символов',
            'coordinates.string' => 'Поле "Координаты организации" должно быть строкой',
            'coordinates.max' => 'Поле "Координаты организации" не должно превышать :max символов',
            'description.string' => 'Поле "Краткое описание услуг" должно быть строкой',
            'description.max' => 'Поле "Краткое описание услуг" не должно превышать :max символов',
            'img.image' => 'Файл не подходит по формату. Поддерживаются: jpg, jpeg, png, bmp, gif, svg',
            'img.max' => 'Поле "Логотип" не должно превышать :max kb',
            'agree.required' => 'Для добавления организации в каталог, необходимо принять Партнёрское соглашение',
            'agree.boolean' => 'Неверный формат в поле соглашения. Обратитесь к администрации',
            'active.boolean' => 'Неверный формат в поле публикации. Обратитесь к AVNK',
        ];
    }
}
