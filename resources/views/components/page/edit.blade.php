@include('components.validation')

<form class="w-full max-w-[640px] p-4 border border-gray-500 shadow-lg rounded-md mx-auto bg-[#222222]" action="{{ route('pages.update', [$page]) }}" method="post">
    @method('PUT')
    @csrf

    <div class="form__wrap">
        <label class="label" for="ico">Иконка</label>
        <input class="input" type="text" placeholder="Здесь должен быть код SVG иконки" id="ico" name="ico" value="{{ old('svg') ?? $page->ico }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="title">Название ссылки</label>
        <input class="input" type="text" placeholder="Название которое будут видеть посетители" id="title" name="title" value="{{ old('title') ?? $page->title }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="slug">Адрес ссылки</label>
        <input class="input" type="text" placeholder="Адрес в строке браузера" id="slug" name="slug" value="{{ old('slug') ?? $page->slug }}" required>
    </div>

    <div class="form__wrap">
        <label for="text">Статья</label>
        <textarea id="text" name="text">{{ old('text') ?? $page->text }}</textarea>
    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Изменить</button>
    </div>
</form>
