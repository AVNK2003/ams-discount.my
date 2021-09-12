@include('components.validation')

<form class="form" action="{{ route('login') }}" class="form" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form__wrap">
        <label class="label" for="tel">Телефон</label>
        <input class="input @error('tel') is-invalid @enderror" type="tel" placeholder="+7 (978) 123-45-67" id="tel" name="tel" value="{{ old('tel') }}" required>
{{--        @error('tel')

            <span class="invalid-message">
                <strong>{{ $message }}</strong>
            </span>
        @enderror--}}
    </div>

    <div class="form__wrap">
        <label class="label" for="password">Пароль</label>
        <input class="input @error('password') is-invalid @enderror" type="password" placeholder="Пароль" id="password" name="password" value="{{ old('password') }}" required>
{{--        @error('password')
            <span class="invalid-message">
                <strong>{{ $message }}</strong>
            </span>
        @enderror--}}
    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full"
                type="submit">
            Войти
        </button>
    </div>

    <div class="form__footer">
        <div class="w-1/2 text-center">
                <a
                    class="link"
                    href="{{ route('register') }}">
                    Регистрация
                </a>
        </div>
        <div class="w-1/2 text-center">
                <a
                    class="link"
                    href="{{ route('password.email') }}">
                    Забыли пароль?
                </a>
        </div>
    </div>

</form>
