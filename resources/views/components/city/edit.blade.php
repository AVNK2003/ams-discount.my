@include('components.validation')

<form class="form" action="{{ route('cities.update', [$city]) }}" class="form" method="post">
    @method('PUT')
    @csrf

    <div class="form__wrap">
        <label class="label" for="name">Город</label>

        <input
            class="input"
            type="text"
            placeholder="Ялта"
            id="name"
            name="name"
            value="{{ old('name') ?? $city->name }}"
            required
        >

    </div>

    <div class="form__wrap">
        <label class="label" for="slug">Ссылка (латиница)</label>
        <input
            class="input"
            type="text"
            placeholder="yalta"
            id="slug"
            name="slug"
            value="{{ old('slug') ?? $city->slug }}"
            required
        >

    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Изменить</button>
    </div>
</form>
