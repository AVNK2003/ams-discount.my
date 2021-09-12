@extends('layouts.layout')
@section('title', '- Проверка отзыва')
@section('linksAside') <x-aside-links-admins />@endsection
@section('content')

    <h1 class="mt-6 mb-4 text-xl text-center">Проверка отзыва</h1>

    <div class="flex items-start bg-gray-900 rounded-md shadow-md py-4 mt-4">
        <div class="mx-6 w-full">
            <div class="flex items-center justify-between">
                <div>

                    {{--                            Имя--}}
                    <p>
                        <span class="text-gray-500 font-bold">{{$comment->name}}</span>
                    </p>

                    {{--                            Рейтинг--}}
                    <div class="inline-block text-yellow-500">
                        @for($i=0; $i<5; $i++)
                            @if($i<$comment->rating)
                                <i class="fas fa-star fa-sm"></i>
                            @else
                                <i class="far fa-star fa-sm text-gray-500"></i>
                            @endif
                        @endfor
                    </div>
                </div>

                {{--                        Дата--}}
                <div class="text-gray-500">{{ $comment->created_at }}</div>
            </div>
            <div class="mt-3">

                {{--                        Отзыв--}}
                <p class="mt-1">{{$comment->review}}</p>
            </div>


            <div class="mt-4">

                <div class="flex justify-end py-2 px-4">

                    {{--                            Одобрить--}}
                    <form action="{{route('togglePublish', $comment)}}" method="post">
                        @method('PUT')
                        @csrf
                    <button class="border border-green-500 rounded-md text-green-500 px-4 py-2 hover:bg-green-500 hover:text-black focus:outline-none">
                        {{$comment->published ?'Скрыть' :'Опубликовать'}}
                    </button>
                    </form>
                    {{--                            Удалить--}}
                    <form action="{{route('comments.destroy', $comment)}}" method="post">
                        @method('DELETE')
                        @csrf
                    <button class="border border-red-500 rounded-md text-red-500 px-4 py-2 ml-2 hover:bg-red-500 hover:text-black focus:outline-none">
                        Удалить
                    </button>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <a
        class="block w-40 text-center mx-auto mt-4 py-2 px-4 rounded-md border hover:bg-red-800"
        href="{{ route('comments.index') }}"
    >
        Вернуться
    </a>
@endsection

