@include('components.validation')

<form class="form" action="{{ route('categories.update', [$category]) }}" class="form" method="post">
    @method('PUT')
    @csrf

    <div class="form__wrap">
        <label class="label" for="name">Категория</label>

        <input value="{{ old('name') ?? $category->name }}" class="input" type="text"
            placeholder="Название" id="name" name="name" required>
    </div>

    <div class="form__wrap">
        <label
            class="label"
            for="slug"
        >
            Ссылка (латиница)
        </label>
        <input
            class="input"
            type="text"
            placeholder="example"
            id="slug"
            name="slug"
            value="{{ old('slug') ?? $category->slug }}"
            required
        >
    </div>

    <div class="form__wrap">
        <label
            class="label"
            for="color"
        >
            Цвет
        </label>
        <input
            class="p-1 h-10 w-14 rounded-md"
            type="color"
            id="color"
            name="color"
            value="{{ old('color') ?? $category->color }}"
            required
        >
    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Изменить</button>
    </div>
</form>
