@include('components.validation')

<form class="form" action="{{ route('categories.store') }}" method="post">
    @csrf

    <div class="form__wrap">
        <label
            class="label"
            for="name"
        >
            Категория
        </label>

        <input
            class="input"
            type="text"
            placeholder="Название"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
        >
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
            value="{{ old('slug') }}"
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
            value="#FF0000"
            required
        >
    </div>
    <div class="form__wrap">
        <button
            class="btn btn-red w-full"
            type="submit"
        >
            Добавить
        </button>
    </div>
</form>
