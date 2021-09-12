@extends('layouts.layout')
@section('title', '- '.$partner->name)
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    @include('components.validation')
    <h1 class="text-2xl mb-2 text-center">{{ $partner->name }}</h1>

    <div class="flex flex-col mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-2">
                <div class="overflow-hidden rounded-md shadow-md">
                    <table class="min-w-full overflow-x-scroll divide-y divide-gray-500">
                        <thead class="bg-black">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Телефон
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Email
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Регистрация
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">

                        <tr class="hover:bg-black">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <a href="tel:+{{ preg_replace("/[^0-9]/", '', $partner->tel) }}"
                                   class="text-sm border-dashed border-b hover:border-solid">
                                    {{ $partner->tel }}
                                </a>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <a href="mailto:{{ $partner->email }}"
                                   class="text-sm border-dashed border-b hover:border-solid">
                                    {{ $partner->email }}
                                </a>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <span class="text-sm">
                                {{ \Carbon\Carbon::parse($partner->created_at)->translatedFormat('j F Y, h:i') }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex justify-around">
                                    <form action="{{ route('partners.admin.toggle', $partner) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <button type="submit" class="text-sm hover:underline focus:outline-none">
                                            @if($partner->is_admin)Убрать из админов @else Сделать админом @endif
                                        </button>
                                    </form>
                                    <a href="{{route('partners.edit',[$partner])}}">
                                        <svg class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
                                            <use xlink:href="/img/sprite.svg#edit"></use>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    @if($partner->companies->count() > 0)
        <h2 class="text-xl mb-2 text-center">Добавленные организации</h2>
        <div class="flex flex-col mt-2">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full py-2 align-middle px-2">
                    <div class="overflow-hidden rounded-md shadow-md">
                        <table class="min-w-full overflow-x-scroll divide-y divide-gray-500">
                            <thead class="bg-black">
                            <tr>
                                <th scope="col"
                                    class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                    Организация
                                </th>
                                <th scope="col"
                                    class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                    Просмотров
                                </th>
                                <th scope="col"
                                    class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                    Статус
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">

                            @foreach($partner->companies as $company)
                                <tr class="transition-all hover:bg-black">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <a href="{{route('companies.show',[$company])}}">
                                                    <img
                                                        class="max-w-10 max-h-10 rounded"
                                                        src="
                                                    @if($company->img)
                                                            /img/uploads/thumbnail/{{$company->img}}
                                                        @else
                                                            /img/logo.png
@endif
                                                            "
                                                        alt=""
                                                    />
                                                </a>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium">
                                                    <a href="{{route('companies.show',[$company])}}"
                                                       class="hover:underline">
                                                        {{$company->title}}
                                                    </a>
                                                </div>
                                                @foreach($company->categories as $category)
                                                    @if($category)
                                                        <a href="{{route('categories.show',[$category])}}"
                                                           class="inline-block text-sm text-gray-400 hover:underline mr-1">
                                                            {{$category->name}}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-400">{{ $company->views }}</span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        {{--                                    @dd($company)--}}
                                        <form action="{{ route('toggleActive', $company) }}" method="post">
                                            @method('PUT')
                                            @csrf
                                            <button class="focus:outline-none" type="submit">
                                                @if($company->active)
                                                    <span
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-green-600 bg-green-100 rounded-full">Активна</span>
                                                @else
                                                    <span
                                                        class="inline-flex px-2 text-xs font-semibold leading-5 text-red-600 bg-red-100 rounded-full">Неактивна</span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex justify-around">
                                            <a href="{{route('companies.edit',[$company])}}">
                                                <svg class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
                                                    <use xlink:href="/img/sprite.svg#edit"></use>
                                                </svg>
                                            </a>
                                            <form action="{{route('companies.destroy',[$company])}}" method="POST"
                                                  x-data x-ref="form">
                                                @method('delete')
                                                @csrf
                                                <button class="ml-2 focus:outline-none"
                                                        x-on:click.prevent="if (confirm('Вы точно хотите удалить {{$company->title}}?')) $refs.form.submit()"
                                                        type="submit">
                                                    <svg class="h-6 w-6 text-red-600 hover:text-red-500">
                                                        <use xlink:href="/img/sprite.svg#delete"></use>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-4 text-center ">
            Данный пользователь не добавлял организации в каталог
        </div>
    @endif
@endsection
