<section class="mt-4 rounded-md border overflow-hidden">
    <h2 class="p-4 bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">Отзывы</h2>
    <div class="p-4 bg-gradient-to-b from-[#222222] via-black to-[#222222]">

        <x-success-modal/>
        @php $collect = session('likes', collect([])) @endphp
        @foreach($comments as $comment)
            <div class="flex items-start bg-gray-900  rounded py-4 mb-4">
                <div class="mx-6 w-full">
                    <div class="flex items-center justify-between">
                        <div>

                            {{--                            Имя--}}
                            <p>
                                <span class="text-gray-500 font-bold">{{$comment->name}}</span>
                            </p>

                            {{--                            Рейтинг--}}
                            <div class="flex text-yellow-500">
                                @for($i=0; $i<5; $i++)
                                    @if($i<$comment->rating)
                                        <svg class="h-4 w-4 mr-[2px]">
                                            <use xlink:href="/img/sprite.svg#star-full"></use>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 mr-[2px] text-gray-500">
                                            <use xlink:href="/img/sprite.svg#star-empty"></use>
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        {{--                        Дата--}}
                        <div class="text-gray-500">
                            {{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat('j F Y') }}
                        </div>
                    </div>
                    <div class="mt-3">

                        {{--                        Отзыв--}}
                        <p class="mt-1">{{$comment->review}}</p>
                    </div>


                    <div class="flex mt-4 text-sm text-gray-500 fill-current">

                        <div class="flex ml-auto items-center bg-gray-800 rounded py-2 px-4">
                            <span>Полезный отзыв?</span>

                            {{--                            Лайк--}}

                            <button wire:click="setLike({{$comment->id}})"
                                    class="flex items-center ml-6 focus:outline-none @if($collect->get($comment->id) == 'like') text-gray-300 @endif">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M11 0h1v3l3 7v8a2 2 0 0 1-2 2H5c-1.1 0-2.31-.84-2.7-1.88L0 12v-2a2 2 0 0 1 2-2h7V2a2 2 0 0 1 2-2zm6 10h3v10h-3V10z"/>
                                </svg>
                                <span class="ml-2">{{$comment->likes}}</span>
                            </button>


                            {{--                            Дизлайк--}}

                            <button wire:click="setDislike({{$comment->id}})"
                                    class="flex items-center ml-4 focus:outline-none @if($collect->get($comment->id) == 'dislike') text-gray-300 @endif">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M11 20a2 2 0 0 1-2-2v-6H2a2 2 0 0 1-2-2V8l2.3-6.12A3.11 3.11 0 0 1 5 0h8a2 2 0 0 1 2 2v8l-3 7v3h-1zm6-10V0h3v10h-3z"/>
                                </svg>
                                <span class="ml-2">{{$comment->dislikes}}</span>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        @endforeach
        {{--            Форма для отзыва--}}
        <form action="#" class="rounded-md overflow-hidden mt-2" wire:submit.prevent="submit">
            {{--            <input type="number" name="company_id" value="{{ $company->id }}" hidden>--}}
            {{--            <input wire:model="company_id" value="{{$company->id}}">--}}
            <h3 class="text-xl">Оставить отзыв</h3>
            <div class="mt-2">
                <label class="block" for="name">Имя *</label>
                <input class="max-w-[500px] text-gray-900 rounded-md" type="text" id="name" name="name"
                       wire:model.defer="name">
            </div>
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            <div class="mt-2">
                <label for="rating" class="block">Оценка *</label>
                <div class="rating-area">
                    <input type="radio" id="star-5" name="rating" value="5" wire:model.defer="rating">
                    <label for="star-5" title="Оценка «5»">
                        <svg class="h-8 w-8">
                            <use xlink:href="/img/sprite.svg#star-full"></use>
                        </svg>
                    </label>
                    <input type="radio" id="star-4" name="rating" value="4" wire:model.defer="rating">
                    <label for="star-4" title="Оценка «4»">
                        <svg class="h-8 w-8">
                            <use xlink:href="/img/sprite.svg#star-full"></use>
                        </svg>
                    </label>
                    <input type="radio" id="star-3" name="rating" value="3" wire:model.defer="rating">
                    <label for="star-3" title="Оценка «3»">
                        <svg class="h-8 w-8">
                            <use xlink:href="/img/sprite.svg#star-full"></use>
                        </svg>
                    </label>
                    <input type="radio" id="star-2" name="rating" value="2" wire:model.defer="rating">
                    <label for="star-2" title="Оценка «2»">
                        <svg class="h-8 w-8">
                            <use xlink:href="/img/sprite.svg#star-full"></use>
                        </svg>
                    </label>
                    <input type="radio" id="star-1" name="rating" value="1" wire:model.defer="rating">
                    <label for="star-1" title="Оценка «1»">
                        <svg class="h-8 w-8">
                            <use xlink:href="/img/sprite.svg#star-full"></use>
                        </svg>
                    </label>
                </div>
            </div>
            @error('rating') <span class="text-red-600">{{ $message }}</span> @enderror
            <div class="mt-2">
                <label class="block" for="review">Отзыв</label>
                <textarea
                    class="text-gray-900 w-full h-40 resize-none rounded-md focus:ring-0"
                    name="review" id="review" wire:model.defer="review"></textarea>
            </div>
            @error('review') <span class="text-red-600">{{ $message }}</span> @enderror
            <button class="mt-2 btn btn-red" type="submit">Отправить</button>
        </form>

    </div>
</section>
