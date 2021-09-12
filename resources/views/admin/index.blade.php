@extends('layouts.layout')
@section('title', '- Личный кабинет')
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>

    <h1 class="text-2xl mb-2 text-center">Здравствуйте, {{ auth()->user()->name }}!</h1>

    <div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
        <div
                class="px-4 py-2 bg-black bg-opacity-50 transition-all duration-300 border rounded-lg shadow-sm hover:bg-red-900 hover:bg-opacity-75 hover:cursor-pointer"
                x-on:click.prevent="document.location.href = '{{ route('companies.index') }}'">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Организации</span>
                    <div class="flex space-x-2">
                        @php
                        if (auth()->user()->is_admin) {
                            $companies = \App\Models\Company::without(['cities','categories'])->get();
                        } else {
                            $companies = \App\Models\Company::where('id', auth()->user()->id)->without(['cities','categories'])->get();
                        }
                        @endphp
                        <span class="text-lg font-semibold">{{ $companies->count() }}</span>
                        @if($companies->contains('active', 0))
                            <div class="text-sm text-red-600">(+ {{ $companies->where('active', 0)->count() }}
                                неактивны)
                            </div>
                        @endif
                    </div>
                </div>
                <div class="p-2 rounded-md">
                    <span>
                    <svg class="h-12 w-12 text-yellow-800">
                        <use xlink:href="/img/sprite.svg#org"></use>
                    </svg>
                </span>
                </div>
            </div>
        </div>

        @if(auth()->user()->is_admin)
        <div class="px-4 py-2 bg-black bg-opacity-50 transition-all duration-300 border rounded-lg shadow-sm hover:bg-red-800 hover:bg-opacity-75 hover:cursor-pointer"
                x-on:click.prevent="document.location.href = '{{ route('partners.index') }}'">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Партнеры</span>
{{--                    <span class="text-lg font-semibold">{{ \App\Models\User::where('is_admin', false)->count() }}</span>--}}
                    <div class="flex space-x-2">
                        @php $partners = \App\Models\User::where('is_admin', false)->get() @endphp
                        <span class="text-lg font-semibold">{{ $partners->count() }}</span>
                        @if($partners->where('created_at','>',now()->subDay(1))->count())
                            <div class="text-sm text-red-600">
                                (+ {{ $partners->where('created_at','>',now()->subDay(1))->count() }} за сутки)
                            </div>
                        @endif
                    </div>
                </div>
                <div class="p-2 rounded-md">
                <span>
                    <svg class="h-12 w-12 text-red-200">
                        <use xlink:href="/img/sprite.svg#users"></use>
                    </svg>
                </span>
                </div>
            </div>
        </div>

        <div
                class="px-4 py-2 bg-black bg-opacity-50 transition-all duration-300 border rounded-lg shadow-sm hover:bg-red-800 hover:bg-opacity-75 hover:cursor-pointer"
                x-on:click.prevent="document.location.href = '{{ route('comments.index') }}'">
            <div class="flex items-start justify-between">
                <div class="flex flex-col space-y-2">
                    <span class="text-gray-400">Отзывы</span>
                    <div class="flex space-x-2">
                        @php $comments = \App\Models\Comment::all() @endphp
                        <span class="text-lg font-semibold">{{ $comments->count() }}</span>
                        @if($comments->contains('published', 0))
                            <div class="text-sm text-red-600">(+ {{ $comments->where('published', 0)->count() }}
                                новых)
                            </div>
                        @endif
                    </div>
                </div>
                <div class="p-2 rounded-md">
                <span>
                    <svg class="h-12 w-12 text-blue-300">
                        <use xlink:href="/img/sprite.svg#reviews"></use>
                    </svg>
                </span>
                </div>
            </div>
        </div>
        @endif
        {{--
            <div class="p-4 transition-all border rounded-lg shadow-sm hover:bg-red-800 hover:bg-opacity-75 hover:cursor-pointer" x-on:click.prevent="document.location.href = '{{ route('partner.index') }}'">
                <div class="flex items-start justify-between">
                    <div class="flex flex-col space-y-2">
                        <span class="text-gray-400">Сообщения</span>
                        <span class="text-lg font-semibold">{{ \App\Models\Message::count() }}</span>
                    </div>
                    <div class="p-2 rounded-md">
                        <span>
                            <svg  class="h-12 w-12 text-gray-400">
                                <use xlink:href="/img/sprite.svg#users"></use>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            --}}
    </div>
@endsection
