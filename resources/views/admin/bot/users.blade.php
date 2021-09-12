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
					<x-table.table>
						<thead class="bg-black">
						<tr>
							<x-table.head-cell>ID</x-table.head-cell>
							<x-table.head-cell>Имя в телеграм</x-table.head-cell>
							<x-table.head-cell>Имя</x-table.head-cell>
							<x-table.head-cell>Фамилия</x-table.head-cell>
							<x-table.head-cell>Подключился к боту</x-table.head-cell>
							<x-table.head-cell>Обращался к боту</x-table.head-cell>
							<x-table.head-cell>Состояние</x-table.head-cell>
						</tr>
						</thead>
						<tbody class="bg-black bg-opacity-60 divide-y divide-gray-800">
						@foreach($users as $user)
							<x-table.body-row>
								<x-table.body-cell>{{ $user->user_id }}</x-table.body-cell>

								<x-table.body-cell>
									@if($user->user_name)
										<a href="https://t.me/{{ $user->user_name }}"
										   class="text-sm border-dashed border-b hover:border-solid">
											&#64;{{ $user->user_name }}
										</a>
									@endif
								</x-table.body-cell>

								<x-table.body-cell>{{ $user->first_name ?? 'Не указано' }}</x-table.body-cell>

								<x-table.body-cell>{{ $user->last_name ?? 'Не указано' }}</x-table.body-cell>

								<x-table.body-cell>
									<span class="text-sm">{{ $user->created_at }}</span>
								</x-table.body-cell>

								<x-table.body-cell>
									<span class="text-sm">{{ $user->updated_at->diffForHumans() }}</span>
								</x-table.body-cell>

                                <x-table.body-cell>
                                    @if($user->active)
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-600 bg-green-100 rounded-full">Активен</span>
                                    @else
                                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-red-600 bg-red-100 rounded-full">Заблокирован</span>
                                    @endif
                                </x-table.body-cell>
                            </x-table.body-row>
						@endforeach
						</tbody>
					</x-table.table>
				</div>
			</div>
		</div>
	</div>
@endsection
