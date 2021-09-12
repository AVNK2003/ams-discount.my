<form action="{{ route('password.email') }}" class="form" method="POST">
    @csrf

    <div class="form__wrap">
        <label class="label" for="email">Email</label>
        <input class="input @error('email') is-invalid @enderror" type="email" placeholder="Email указанный при регистрации" id="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Выслать ссылку на почту</button>
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
                href="{{ route('login') }}">
                Страница входа
            </a>
        </div>
    </div>
</form>
