@extends('layouts.layout')
@section('title', '- Профиль пользователя')
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    <h1 class="text-2xl mb-2 text-center">Профиль пользователя</h1>
    <x-validation/>
    <div class="flex flex-wrap gap-4">
        <form action="{{ route('profile.update', auth()->user()) }}" class="form" method="post">
            @method('PUT')
            @csrf
            <div class="form__wrap">
                <label class="label" for="name">Имя</label>
                <input class="input @error('name') is-invalid @enderror" type="text" placeholder="Как к вам обращаться"
                       id="name" name="name" value="{{ old('name') ?? auth()->user()->name }}" required>
            </div>

            <div class="form__wrap">
                <label class="label" for="email">Email</label>
                <input class="input @error('email') is-invalid @enderror" type="text" placeholder="mail@mail.ru"
                       id="email" name="email" value="{{ old('email') ?? auth()->user()->email }}" required>
            </div>

            <div class="form__wrap">
                <label class="label" for="tel">Телефон</label>
                <input class="input @error('tel') is-invalid @enderror" type="tel" placeholder="+79781234567" id="tel"
                       name="tel" value="{{ old('tel') ?? auth()->user()->tel }}" required>
            </div>

            <div class="form__wrap">
                <label class="label" for="telegram_id">Телеграм ID</label>
                <input class="input @error('telegram_id') is-invalid @enderror" type="text" placeholder="1234567890" id="telegram_id"
                       name="telegram_id" value="{{ old('telegram_id') ?? auth()->user()->telegram_id }}">
            </div>

            <div class="form__wrap">
                <button class="btn btn-red w-full" type="submit">Изменить</button>
            </div>
        </form>

        <div class="mx-auto">
            <h2 class="text-xl mb-2 text-center">Изменение пароля</h2>

            <form action="{{ route('passwordChange') }}" class="form" method="post">
                @method('PUT')
                @csrf
                <div class="form__wrap">
                    <label class="label" for="password">Пароль</label>
                    <input class="input" type="password" placeholder="Новый пароль"
                           id="password" name="password" required>
                </div>

                <div class="form__wrap">
                    <label class="label" for="password_confirmation">Подтверждение пароля</label>
                    <input class="input" type="password" placeholder="Подтверждение пароля"
                           id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="form__wrap">
                    <button class="btn btn-red w-full" type="submit">Изменить пароль</button>
                </div>
            </form>
        </div>
        @if(auth()->user()->tel == '+7 (978) 109-11-56')
            <div class="mx-auto">
                <h2 class="text-xl mb-2 text-center">Кэш</h2>
                <form action="{{ route('clearAllConfig') }}" class="form mt-4 text-center" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-red">Очистить кэш</button>
                </form>
            </div>
        @endif
    </div>
@endsection

@section('scriptsFooter')
    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputElement = document.querySelector('#tel');
            const maskOptions = {
                mask: '+{7} (000) 000-00-00',
            }
            IMask(inputElement, maskOptions);
        });
    </script>
@endsection
