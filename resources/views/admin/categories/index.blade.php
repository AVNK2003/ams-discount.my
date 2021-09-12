@extends('layouts.layout')
@section('title', '- Категории')
@section('linksAside') <x-aside-links-admins />@endsection
@section('content')
    <x-success-session />
    <h1 class="text-2xl mb-2 text-center">Категории</h1>

        <a class="ml-3 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800" href="{{ route('categories.create') }}">Добавить категорию</a>
    <div class="flex flex-col mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-2">
                <div class="overflow-hidden rounded-md shadow-md">
                    <table class="min-w-full overflow-x-scroll divide-y divide-gray-700">
                        <thead class="bg-black">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Название
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Ссылка
                            </th>
                            <th scope="col"
                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Цвет
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">
                        @foreach($categories as $category)
                            <tr class="transition-all duration-300 hover:bg-black">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-300">
                                        <a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-300">
                                        {{ $category->slug }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="w-6 h-6" style="background-color: {{ $category['color'] }}"></div>
                                </td>
                                <td class="flex justify-around px-3 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="{{ route('categories.edit', [$category]) }}">
                                        <svg  class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
                                            <use xlink:href="/img/sprite.svg#edit"></use>
                                        </svg>
                                    </a>
                                    <form
                                        action="{{ route('categories.destroy', [$category]) }}"
                                        method="POST"
                                        x-data x-ref="form">
                                        @method('delete')
                                        @csrf
                                        <button
                                            class="ml-2 focus:outline-none"
                                            x-on:click.prevent="if (confirm('Вы точно хотите удалить {{$category->name}}?')) $refs.form.submit()" type="submit">
                                            <svg  class="h-6 w-6 text-red-600 hover:text-red-500">
                                                <use xlink:href="/img/sprite.svg#delete"></use>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
