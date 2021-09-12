@extends('layouts.layout')
@section('title')- Редактирование партнера {{ $partner->name }} @endsection
@section('linksAside') <x-aside-links-admins />@endsection
@section('content')
    <x-success-session/>

    <h1 class="mt-6 mb-4 text-xl text-center">Редактирование партнера {{ $partner->name }}</h1>

    @include('components.partners-edit-form')

    <a
        class="block w-40 text-center mx-auto mt-4 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800"
        href="{{ url()->previous() }}"
    >
        Вернуться
    </a>
@endsection
