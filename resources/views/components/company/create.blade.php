<form action="{{ route('companies.store') }}" class="form" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form__wrap">
        <label class="label" for="org">Форма организации</label>
        <select class="input" name="org" id="org">
            <option value="ИП" selected>ИП</option>
            <option value="ООО">ООО</option>
            <option value="Самозанятый">Самозанятый</option>
        </select>
    </div>

    <div class="form__wrap">
        <label class="label" for="title">Название организации *</label>
        <input class="input" type="text" placeholder="Название организации" id="title" name="title" value="{{ old('title') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="discount">Размер скидки *</label>
        <input class="input" type="text" placeholder="Размер скидки" id="discount" name="discount" value="{{ old('discount') }}">
    </div>
{{--    @php $select = old('user_id') ?? auth()->user()->id @endphp--}}
    @if(auth()->user()->is_admin)
        <div class="form__wrap">
            <label class="label" for="user_id">Владелец организации</label>
            <select class="input" name="user_id" id="user_id">
                @foreach(\App\Models\Partner::all(['id', 'name', 'tel'])->sortBy('tel') as $partner)
                    <option value="{{ $partner->id }}"
                        @if(old('user_id', auth()->user()->id) == $partner->id)
                            selected
                        @endif>
                        {{ $partner->tel }} - {{ $partner->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form__wrap">
            <label class="label" for="priority">Приоритет в каталоге</label>
            <input class="input" type="number" id="priority" name="priority" value="{{ old('priority', 0) }}">
        </div>

        <div class="form__wrap">
            <label class="label" for="date">Дата окончания</label>
            <input type="date" class="input" id="date" name="date_end" value="{{ old('date_end') }}"/>
        </div>
    @else
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
    @endif

    <div class="form__wrap">
        <label class="label" for="category">Категория *</label>
        <div class="flex wrap border rounded-md py-2 px-4" id="category">
            <div class="group-form">
                @foreach(\App\Models\Category::all(['id', 'name', 'slug'])->sortBy('name') as $category)
                    <div class="flex items-center">
                        <input class="focus:ring-1 focus:ring-red-600 text-red-600 rounded" type="checkbox" id="{{ $category->slug }}" name="categories[]" value="{{ $category->id }}" @if(is_array(old('categories')) && in_array($category->id, old('categories'))) checked @endif>
                        <label class="label ml-1" for="{{ $category->slug }}">{{ $category->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="form__wrap">
        <label class="label" for="select-city">Город *</label>
        <div class="flex wrap border rounded-md py-2 px-4" id="select-city">
            <div class="group-form">
                @foreach(\App\Models\City::all(['id', 'name', 'slug'])->sortBy('name') as $city)
                    <div class="flex items-center">
                        <input class="focus:ring-1 focus:ring-red-600 text-red-600 rounded" type="checkbox" id="{{ $city->slug }}" name="cities[]" value="{{ $city->id }}" @if(is_array(old('cities')) && in_array($city->id, old('cities'))) checked @endif>
                        <label class="ml-1 label" for="{{ $city->slug }}">{{ $city->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="form__wrap">
        <label class="label" for="address">Адрес организации</label>
        <input class="input" type="text" placeholder="Адрес организации" id="address" name="address" value="{{ old('address') }}">
    </div>

    <div class="form__wrap">
        <label class="label" for="time-work">График работы</label>
        <input class="input" type="text" placeholder="График работы" id="time-work" name="working_hours" value="{{ old('working_hours') }}">
    </div>

    <div class="form__wrap">
        <label class="label" for="phone">Телефон</label>
        <input class="input" type="text" placeholder="Телефон" id="phone" name="tel" value="{{ old('tel') }}">
    </div>

    <div class="form__wrap">
        <label class="label" for="site">Сайт</label>
        <input class="input" type="url" placeholder="https://example.com" id="site" name="site" value="{{ old('site') }}">
    </div>

    <div class="flex w-full wrap mb-4">
        <div class="group-form">
            <label class="label ml-1" for="instagram">Instagram</label>
            <input class="input" type="url" placeholder="Instagram" id="instagram" name="instagram" value="{{ old('instagram') }}">
            <label class="label ml-1" for="vk">VK</label>
            <input class="input" type="url" placeholder="VK" id="vk" name="vk" value="{{ old('vk') }}">
        </div>
        <div class="group-form">
            <label class="label ml-1" for="facebook">Facebook</label>
            <input class="input" type="url" placeholder="Facebook" id="facebook" name="facebook" value="{{ old('facebook') }}">
            <label class="label ml-1" for="youtube">YouTube</label>
            <input class="input" type="url" placeholder="YouTube" id="youtube" name="youtube" value="{{ old('youtube') }}">
        </div>
    </div>

    <div class="form__wrap">
        <label class="label" for="coordinates">Координаты организации</label>
        <input class="input" type="text" id="coordinates" name="coordinates" value="{{ old('coordinates') }}" hidden>
        <div class="h-[300px] rounded-md overflow-hidden" id="map"></div>
        <span class="text-gray-400 text-xs">Поставьте метку на карте, где находится Ваша организация</span>
    </div>

    <div class="form__wrap">
        <label class="label" for="description">Краткое описание услуг</label>
        <textarea class="input" placeholder="Краткое описание" id="description" name="description" rows="4">{{ old('description') }}</textarea>
    </div>

    <div class="form__wrap">
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
        <label class="label" for="image">Логотип (размер файла не более 1мб)</label>
        <input class="rounded-md" placeholder="Логотип" type="file" id="image" name="img" accept="image/gif, image/jpeg, image/png">
    </div>

    <div class="form__wrap">
        @if(auth()->user()->is_admin)
            <div class="form__wrap flex items-center">
                <input type="checkbox" class="focus:ring-1 focus:ring-red-600 text-red-600 rounded" id="active" name="active" value="1" checked/>
                <label class="ml-1 label" for="active">Опубликовать в каталоге</label>
            </div>
            <input type="checkbox" name="agree" class="hidden" id="agree"  value="1" checked>
        @else
            <label class="label">Соглашение *</label>
            <div>
                <input type="checkbox" name="agree" class="focus:ring-1 focus:ring-red-600 text-red-600 rounded" id="agree" value="1" required>
                <label for="agree" class="ml-1 text-sm">Согласен с условиями <a href="https://yadi.sk/i/Vj6nEeXNJvozHg" class="link">Партнёрского соглашения</a></label>
            </div>
        @endif
    </div>

    <div class="text-right">
        <button class="btn btn-red" type="submit">Отправить</button>
    </div>
</form>
