@extends('layouts.layout')
@section('title', '- Настройки бота')
@section('linksAside')
	<x-aside-links-admins/>@endsection
@section('content')
	<x-success-session/>
	<h1 class="text-2xl mb-2 text-center">Настройки бота</h1>
	<form action="{{ route('updateBotSettings') }}" class="form" method="POST">
		@method('PUT')
		@csrf

		@foreach($settings as $setting)
			<div class="form__wrap">
				<label for="{{ $setting->name }}" class="label">{{ $setting->description }}</label>
				<input value="{{ $setting->value ?? null }}"
				       type="text" id="{{ $setting->name }}" name="{{ $setting->name }}" class="input">
			</div>
		@endforeach

		<div class="flex justify-between">
			<a href="{{ url()->previous() }}" class="py-2 px-4 rounded-md border hover:bg-red-800">Назад</a>
			<button type="submit" class="btn btn-red">Сохранить</button>
		</div>
	</form>
@endsection
