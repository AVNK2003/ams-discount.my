<form action="{{ route('partners.update', $partner) }}" class="form" method="post">
	@method('PUT')
	@csrf
	<div class="form__wrap">
		<label class="label" for="name">Имя</label>
		<input class="input @error('name') is-invalid @enderror" type="text" placeholder="Как к вам обращаться"
		       id="name" name="name" value="{{ old('name') ?? $partner->name }}" required>
	</div>

	<div class="form__wrap">
		<label class="label" for="email">Email</label>
		<input class="input @error('email') is-invalid @enderror" type="text" placeholder="mail@mail.ru"
		       id="email" name="email" value="{{ old('email') ?? $partner->email }}" required>
	</div>

	<div class="form__wrap">
		<label class="label" for="tel">Телефон</label>
		<input class="input @error('tel') is-invalid @enderror" type="tel" placeholder="+79781234567" id="tel"
		       name="tel" value="{{ old('tel') ?? $partner->tel }}" required>
	</div>

	<div class="form__wrap">
		<label class="label" for="telegram_id">Телеграм ID</label>
		<input class="input @error('telegram_id') is-invalid @enderror" type="text" placeholder="1234567890" id="telegram_id"
		       name="telegram_id" value="{{ old('telegram_id') ?? $partner->telegram_id }}">
	</div>

	<div class="form__wrap">
		<button class="btn btn-red w-full" type="submit">Изменить</button>
	</div>
</form>