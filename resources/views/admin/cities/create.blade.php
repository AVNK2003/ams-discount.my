@extends('layouts.layout')
@section('title', '- Добавление города')
@section('linksAside') <x-aside-links-admins />@endsection
@section('content')


    <h3 class="mt-6 mb-4 text-xl text-center">Добавление города</h3>

    <x-city.create />

    <a
        class="block w-40 text-center mx-auto mt-4 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800"
        href="{{ route('cities.index') }}"
    >
        Вернуться
    </a>
@endsection
