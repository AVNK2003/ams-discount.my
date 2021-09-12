@extends('layouts.layout')
@section('title', '- Сообщения бота')
{{--@section('scripts')<script src="//cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>@endsection--}}
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    <h1 class="text-2xl mb-2 text-center">Сообщения бота</h1>
    <ul class="m-2">
        <li>Чтобы подставилось имя пользователя, используйте <span class="text-red-500">{$name}</span></li>
        <li>Для ссылок: <span class="text-red-500">&lt;a href="https://адресстраницы.ру"&gt;Отображаемый текст ссылки&lt;/a&gt;</span></li>
        <li>Для <b>жирного</b> текста: <span class="text-red-500">&lt;b&gt;Жирный текст&lt;/b&gt;</span></li>
        <li>Для текста <i>курсивом</i>: <span class="text-red-500">&lt;i&gt;Текст курсивом&lt;/i&gt;</span></li>
    </ul>
    <form action="{{ route('updateBotSettings') }}" class="form" method="POST">
        @method('PUT')
        @csrf

        @foreach($messages as $message)
            <div class="form__wrap">
                <label for="{{ $message->name }}" class="label">{{ $message->description }}</label>
                <textarea id="{{ $message->name }}" name="{{ $message->name }}" rows="10" class="input">{{ $message->value ?? null }}</textarea>
            </div>
        @endforeach

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="py-2 px-4 rounded-md border hover:bg-red-800">Назад</a>
            <button type="submit" class="btn btn-red">Сохранить</button>
        </div>
    </form>
@endsection
{{--@section('scriptsFooter')--}}
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/29.2.0/classic/ckeditor.js"></script>--}}
{{--    <script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/translations/ru.js"></script>--}}
{{--    <style>--}}
{{--        .ck-editor__editable {--}}
{{--            color: #1b1e21;--}}
{{--        }--}}
{{--        .ck-editor a {--}}
{{--            color: blue;--}}
{{--        }--}}
{{--    </style>--}}
{{--    <script>--}}
{{--        @foreach($messages as $message)--}}
{{--        ClassicEditor--}}
{{--                .create( document.querySelector( '#{{ $message->name }}' ), {--}}
{{--                    language: 'ru',--}}
{{--                } )--}}
{{--                .catch( error => {--}}
{{--                    console.error( error );--}}
{{--                } );--}}
{{--        @endforeach--}}
{{--        @foreach($messages as $message)--}}
{{--        CKEDITOR.replace({{ $message->name }} );--}}
{{--        @endforeach--}}
{{--    </script>--}}
{{--@endsection--}}