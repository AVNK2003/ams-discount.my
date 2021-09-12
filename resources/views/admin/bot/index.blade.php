@extends('layouts.layout')
@section('title', '- Бот')
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    <h1 class="text-2xl mb-2 text-center">Бот</h1>
    <div class="flex flex-wrap justify-center">
            <a href="{{ route('botUsers') }}" class="m-2 block py-2 px-4 rounded-md border hover:bg-red-800">
                Пользователи бота: {{ $botUsers }}
            </a>

            <a href="{{ route('setToken') }}" class="m-2 block py-2 px-4 rounded-md border hover:bg-red-800">
                Установить токен бота
            </a>

            <a href="{{ route('botSettings') }}" class="m-2 block py-2 px-4 rounded-md border hover:bg-red-800">
                Настройки бота
            </a>

            <a href="{{ route('botMessages') }}" class="m-2 block py-2 px-4 rounded-md border hover:bg-red-800">
                Настройки сообщений
            </a>
    </div>
@endsection
