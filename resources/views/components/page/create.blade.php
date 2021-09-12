@include('components.validation')

<form class="w-full max-w-[640px] p-4 border border-gray-500 shadow-lg rounded-md mx-auto bg-[#222222]" action="{{ route('pages.store') }}" method="post">
    @csrf

    <div class="form__wrap">
        <label class="label" for="ico">Иконка</label>
        <input class="input" type="text" placeholder="Название иконки" id="ico" name="ico" value="{{ old('ico') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="title">Название страницы</label>
        <input class="input" type="text" placeholder="Название которое будут видеть посетители" id="title" name="title" value="{{ old('title') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="slug">Адрес страницы</label>
        <input class="input" type="text" placeholder="Адрес в строке браузера" id="slug" name="slug" value="{{ old('slug') }}" required>
    </div>

    <div class="form__wrap">
        <label for="text">Статья</label>
        <textarea id="text" name="text">{{ old('text') }}</textarea>
    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Добавить</button>
    </div>
</form>
