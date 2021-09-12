@extends('layouts.layout')
@section('title', '- Организации')
@section('linksAside')
	<x-aside-links-admins/>@endsection
@section('content')
	<x-success-session/>

	<h1 class="text-2xl mb-2 text-center">Организации</h1>

	<a class="ml-3 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800" href="{{ route('companies.create') }}">Добавить
		организацию</a>


	<!-- Table -->

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
							@if(auth()->user()->is_admin)
								<th scope="col"
								    class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
									Владелец
								</th>
							@endif
							<th scope="col"
							    class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-300 uppercase">
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

						@foreach($companies as $company)
							<tr class="hover:bg-black">
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
											<div class="text-sm font-medium text-gray-300">
												<a href="{{route('companies.show',$company)}}"
												   class="hover:cursor-pointer hover:underline">
													{{$company->title}}
												</a>
											</div>
											@if(auth()->user()->is_admin)
												@foreach($company->categories as $category)
													@if($category)
														<a href="{{ route('categories.show', $category) }}"
														   class="inline-block text-sm text-gray-400 mr-1 hover:cursor-pointer  hover:underline">
															{{$category->name}}
														</a>
													@endif
												@endforeach
											@endif
										</div>
									</div>
								</td>

								@if(auth()->user()->is_admin)
									<td class="px-3 py-3 whitespace-nowrap">
										<a href="{{ route('partners.show', $company->partner) }}"
										   class="block text-sm hover:cursor-pointer hover:underline">
											{{ $company->partner->name }}
										</a>
										<a href="mailto:{{ $company->partner->email }}"
										   class="block text-sm text-gray-400 hover:cursor-pointer hover:underline">
											{{ $company->partner->email }}
										</a>
										<a href="tel:+{{ preg_replace("/[^0-9]/", '', $company->partner->tel) }}"
										   class="block text-sm text-gray-400 hover:cursor-pointer hover:underline">
											{{ $company->partner->tel }}
										</a>
									</td>
								@endif
								<td class="px-3 py-3 whitespace-nowrap">
                                    <span class="block text-sm text-gray-400">
                                        {{ $company->views }}
                                    </span>
								</td>
								<td class="px-3 py-3 whitespace-nowrap">
									@if(auth()->user()->is_admin)
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
									@else
										@if($company->active)
											<span
													class="inline-flex px-2 text-xs font-semibold leading-5 text-green-600 bg-green-100 rounded-full">Активна</span>
										@else
											<span
													class="inline-flex px-2 text-xs font-semibold leading-5 text-red-600 bg-red-100 rounded-full">Неактивна</span>
										@endif
									@endif
								</td>
								<td class="px-3 py-4 whitespace-nowrap">
									<div class="flex justify-around">
										<a href="{{route('companies.edit',[$company])}}">
											<svg class="h-6 w-6 text-indigo-600 hover:text-indigo-500">
												<use xlink:href="/img/sprite.svg#edit"></use>
											</svg>
										</a>
										<form
												action="{{route('companies.destroy',[$company])}}"
												method="POST"
												x-data x-ref="form">
											@method('delete')
											@csrf
											<button
													class="ml-2 focus:outline-none"
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
@endsection
