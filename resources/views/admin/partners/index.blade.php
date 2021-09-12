@extends('layouts.layout')
@section('title') - {{ $title }} @endsection
@section('linksAside')<x-aside-links-admins/>@endsection
@section('content')
    <x-success-session/>
    @include('components.validation')
    <h1 class="text-2xl mb-2 text-center">{{ $title }}</h1>

    <div class="flex flex-col mt-2">
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full py-2 align-middle px-2">
                <div class="overflow-hidden rounded-md shadow-md">
                    <x-table.table>
                        <thead class="bg-black">
                        <tr>
                            <x-table.head-cell>Имя</x-table.head-cell>
                            <x-table.head-cell>Email</x-table.head-cell>
                            <x-table.head-cell>Телефон</x-table.head-cell>
                            <x-table.head-cell>Дата регистрации</x-table.head-cell>
                            <x-table.head-cell-hidden />
                        </tr>
                        </thead>
                        <tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">
                        @foreach($partners as $partner)
                            <x-table.body-row>
                                <x-table.body-cell>
                                    <a href="{{ route('partners.show', $partner) }}"
                                       class="text-sm border-dashed border-b hover:border-solid">
                                        {{ $partner->name }}
                                    </a>
                                </x-table.body-cell>

                                <x-table.body-cell>
                                    <a href="mailto:autoclick@mail.ru"
                                       class="text-sm border-dashed border-b hover:border-solid">
                                        {{ $partner->email }}
                                    </a>
                                </x-table.body-cell>

                                <x-table.body-cell>
                                    <a href="tel:+{{ $partner->tel_digits }}"
                                       class="text-sm border-dashed border-b hover:border-solid">
                                        {{ $partner->tel }}
                                    </a>
                                </x-table.body-cell>

                                <x-table.body-cell>
                                    <span class="text-sm">
                                        {{ $partner->created_at }}
                                    </span>
                                </x-table.body-cell>

                                <td class="flex justify-around px-3 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="{{route('partners.edit',[$partner])}}">
                                        <svg class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
                                            <use xlink:href="/img/sprite.svg#edit"></use>
                                        </svg>
                                    </a>

                                    <form action="{{ route('partners.destroy', $partner) }}" method="POST"
                                          x-data x-ref="form">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="ml-2 focus:outline-none"
                                                x-on:click.prevent="if (confirm('Это действие полностью удалит пользователя {{$partner->name}} и все добавленные им организации. Вы уверены, что хотите это сделать?')) $refs.form.submit()">
                                            <svg class="h-6 w-6 text-red-600 hover:text-red-500">
                                                <use xlink:href="/img/sprite.svg#delete"></use>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </x-table.body-row>
                        @endforeach
                        </tbody>
                    </x-table.table>
                </div>
            </div>
        </div>
    </div>
@endsection
