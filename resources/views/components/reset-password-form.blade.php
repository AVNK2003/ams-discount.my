<form class="form" action="{{ route('password.update') }}" method="POST">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="form__wrap">
        <label class="label" for="email">Email</label>
        <input class="input @error('email') is-invalid @enderror" type="email" placeholder="Email указанный при регистрации" id="email" name="email" value="{{ old('email', $request->email) }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="password">Пароль</label>
        <input class="input" type="password" placeholder="Введите новый пароль" id="password" name="password" required autofocus>
    </div>

    <div class="form__wrap">
        <label class="label" for="password_confirmation">Подтверждение пароля</label>
        <input class="input" type="password" placeholder="Введите пароль еще раз" id="password_confirmation" name="password_confirmation" required>
    </div>

    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Изменить пароль</button>
    </div>
</form>
