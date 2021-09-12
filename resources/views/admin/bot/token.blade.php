@extends('layouts.layout')
@section('title', '- Установить токен бота')
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    <h1 class="text-2xl mb-2 text-center">Токен бота</h1>
    <form action="{{ route('setWebhook') }}" class="form" method="POST">
        @csrf
        <div class="form__wrap">
            <label for="url" class="label">Url</label>
            <input type="url" id="url" name="url" class="input">
        </div>

        <div class="form__wrap">
            <label for="token" class="label">Token</label>
            <input value="{{ $token->value ?? null }}" class="input" type="text" id="token" name="token">
        </div>

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="py-2 px-4 rounded-md border hover:bg-red-800">Назад</a>
            <button type="submit" class="btn btn-red">Установить</button>
        </div>
    </form>
@endsection
