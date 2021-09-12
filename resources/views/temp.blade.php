@extends('layouts.layout')
@section('content')
	<div class="flex wrap space-x-4">
		@foreach($companies as $company)
			<div class="card">
				<div class="card__body">
					<div class="card__logo">
						<a href="/company/{{ $company->id }}">
							<img src="@if($company->img)/img/uploads/thumbnail/{{ $company->img }} @else /img/logo.png @endif"
							     alt="{{ $company->title }}"
							     class="logo"
							/>
						</a>
					</div>
					<div class="card__descr">
						<div class="card__header">{{ $company->title }}</div>
						<div class="card__nav">
							@php
								$percent=preg_match('/\d?\d\%/', $company->discount, $percent)?$percent[0]:null
							@endphp
							@if($percent)
								<div class="card__percent mb-2">{{ $percent }}</div>
							@endif
							<div class="card__tags">
								@foreach($company->cities as $city)
									<a class="card__tag" href="{{ route('showCity', $city) }}">{{ $city->name }}</a>
								@endforeach
								@foreach($company->categories as $category)
									<a href="{{ route('showCategory', $category) }}"
									   class="card__tag">{{ $category->name }}</a>
								@endforeach
							</div>
						</div>
						<div class="card__footer">
							<a class="card__btn btn-red" href="{{ route('showCompany', $company) }}">Подробнее</a>
						</div>
					</div>

				</div>
			</div>
		@endforeach
	</div>
	<div class="mt-4">
		@if($currentPage > 0)
			<form action="{{ route('postPaginate') }}" method="POST" class="float-left">
				@csrf
				<input value="{{ $currentPage - 1 }}" name="currentPage" type="text" hidden>
				<button class="btn-red" type="submit">Предыдущая страница</button>
			</form>
		@endif
		@if($currentPage < $companiesCount / $perPage - 1)
			<form action="{{ route('postPaginate') }}" method="POST" class="float-right">
				@csrf
				<input value="{{ $currentPage + 1 }}" name="currentPage" type="text" hidden>
				<button class="btn-red" type="submit">Следующая страница</button>
			</form>
		@endif
	</div>
@endsection
