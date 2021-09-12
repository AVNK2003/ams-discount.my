@extends('layouts.layout')
@section('title', '- Отзывы')
@section('linksAside')<x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>

    <h1 class="text-2xl mb-2 text-center">Отзывы</h1>

    <div class="flex flex-col mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-2">
                <div class="overflow-hidden rounded-md shadow-md">
                    <table class="min-w-full overflow-x-scroll divide-y divide-gray-500">
                        <thead class="bg-black">
                        <tr>
                            <th
                                scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Организация
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Статус
                            </th>
                            <th
                                scope="col"
                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Дата
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">
                        @foreach($comments as $comment)
                            <tr class="transition-all hover:bg-black"
                                title="Оценка {{$comment->rating}}&#10;&#10;{{$comment->review}}">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-300">
                                        <a href="{{ route('comments.show', [$comment]) }}">
                                            {{ $comment->companies->title }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-sm text-gray-300">
                                        <form action="{{route('togglePublish', $comment)}}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <button class="focus:outline-none" type="submit">
                                                @if($comment->published)
                                                    <span
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-green-600 bg-green-100 rounded-full">Опубликован</span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-red-600 bg-red-100 rounded-full">Модерация</span>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-300">
                                        {{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat('j F Y, h:i') }}
                                    </div>
                                </td>
                                <td class="flex items-center justify-around px-3 py-4 text-sm font-medium text-right whitespace-nowrap">

                                    <a href="{{ route('comments.edit', [$comment]) }}">
                                        <svg  class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
                                            <use xlink:href="/img/sprite.svg#edit"></use>
                                        </svg>
                                    </a>
                                    <form
                                        action="{{ route('comments.destroy', [$comment]) }}"
                                        method="POST"
                                        x-data x-ref="form">
                                        @method('delete')
                                        @csrf
                                        <button
                                            class="ml-2 focus:outline-none"
                                            x-on:click.prevent="if (confirm('Вы точно хотите удалить этот комментарий?')) $refs.form.submit()"
                                            type="submit">
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
