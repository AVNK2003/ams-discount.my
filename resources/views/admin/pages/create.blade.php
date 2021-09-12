@extends('layouts.layout')
@section('title', '- Добавление ссылки')
@section('scripts')<script src="//cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>@endsection
@section('linksAside') <x-aside-links-admins />@endsection
@section('content')

    <h3 class="mt-6 mb-4 text-xl text-center">Добавление страницы</h3>

    <x-page.create />

    <a class="block w-40 text-center mx-auto mt-4 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800" href="{{ route('pages.index') }}">Вернуться</a>
@endsection

@section('scriptsFooter')
    <script>
        CKEDITOR.replace( 'text' );
    </script>
@endsection
