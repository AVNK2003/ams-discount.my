<!-- Validation Errors -->
@include('components.validation')

<form action="{{ route('register') }}" class="form" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form__wrap">
        <label class="label" for="name">Имя</label>
        <input class="input @error('name') is-invalid @enderror" type="text" placeholder="Как к вам обращаться" id="name" name="name" value="{{ old('name') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="email">Email</label>
        <input class="input @error('email') is-invalid @enderror" type="text" placeholder="mail@mail.ru" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="tel">Телефон</label>
        <input class="input @error('tel') is-invalid @enderror" type="tel" placeholder="+7 (978) 123-45-67" id="tel" name="tel" value="{{ old('tel') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="telegram_id">Телеграм ID (для уведомлений от бота о записи к Вам клиента (необязательно))</label>
        <input class="input @error('telegram_id') is-invalid @enderror" type="text" placeholder="1234567890" id="telegram_id" name="telegram_id" value="{{ old('telegram_id') }}">
    </div>

    <div class="form__wrap">
        <label class="label" for="password">Пароль</label>
        <input class="input @error('password') is-invalid @enderror" type="password" placeholder="Пароль не менее 8 символов" id="password" name="password" value="{{ old('password') }}" required>
    </div>

    <div class="form__wrap">
        <label class="label" for="password_confirmation">Подтверждение пароля</label>
        <input class="input" type="password" placeholder="Подтверждение пароля" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
    </div>

    <div class="form__wrap">
        <button class="btn btn-red w-full" type="submit">Создать аккаунт</button>
    </div>

    <div class="form__footer justify-center">
        Уже зарегистрированы?&nbsp;
        <a class="link" href="{{ route('login') }}">Войти</a>
    </div>

</form>
