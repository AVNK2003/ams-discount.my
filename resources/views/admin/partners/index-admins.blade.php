@extends('layouts.layout')
@section('title') - {{ $title }} @endsection
@section('linksAside')
    <x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    @include('components.validation')
    <h1 class="text-2xl mb-2 text-center">{{ $title }}</h1>

    <div class="flex flex-col mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-2">
                <div class="overflow-hidden rounded-md shadow-md">
                    <table class="min-w-full overflow-x-scroll divide-y divide-gray-500">
                        <thead class="bg-black">
                        <tr>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Имя
                            </th>
                            <th scope="col"
                                class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Email
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Телефон
                            </th>
                            <th scope="col"
                                class="px-3 py-3 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
                                Дата создания
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">
                        @foreach($admins as $admin)
                            <tr class="transition-all duration-300 hover:bg-black">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <a href="{{ route('partners.show', $admin) }}"
                                       class="text-sm hover:underline">
                                        {{ $admin->name }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <a href="mailto:{{ $admin->email }}"
                                       class="text-sm hover:underline">
                                        {{ $admin->email }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <a href="tel:+{{ preg_replace("/[^0-9]/", '', $admin->tel) }}"
                                       class="text-sm hover:underline">
                                        {{ $admin->tel }}
                                    </a>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span
                                        class="text-sm"> {{ \Carbon\Carbon::parse($admin->created_at)->translatedFormat('j F Y, h:i') }}</span>
                                </td>
                                <td class="flex justify-around px-3 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <form action="{{ route('partners.admin.toggle', $admin) }}" method="POST"
                                          x-data x-ref="form">
                                        @method('delete')
                                        @csrf
                                        <button
                                            x-on:click.prevent="if (confirm('Это действие полностью удалит пользователя {{$admin->name}} и все добавленные им организации. Вы уверены, что хотите это сделать?')) $refs.form.submit()"
                                            type="submit" class="ml-2 focus:outline-none">
                                            <svg class="h-6 w-6 text-red-600 hover:text-red-500">
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
