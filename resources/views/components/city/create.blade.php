@include('components.validation')

<form class="form" action="{{ route('cities.store') }}" class="form" method="post">
    @csrf

    <div class="form__wrap">
        <label
            class="label"
            for="name"
        >
            Город
        </label>

        <input
            class="input"
            type="text"
            placeholder="Ялта"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
        >
        {{--        @error('tel')

                    <span class="invalid-message">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror--}}
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
            placeholder="yalta"
            id="slug"
            name="slug"
            value="{{ old('slug') }}"
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
