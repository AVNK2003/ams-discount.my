@extends('layouts.layout')
@section('title')- {{$company->title}}@endsection
@if($company->coordinates)
@section('scripts')
    <script
            src="https://api-maps.yandex.ru/2.1/?apikey=ff28f6ad-d2a1-40a6-b0fe-3ede2df62e11&lang=ru&load=package.standard"
            type="text/javascript">
    </script>
    @livewireStyles
@endsection
@endif
@section('linksAside')
    {{--  Ссылки в боковой панели если заходят из кабинета  --}}
    @isset($cabinet)
        <x-aside-links-admins/>
        {{--  Ссылки в боковой панели если заходят с главной  --}}
    @else
        <x-aside-links-all/>
    @endif
@endsection
@section('content')

    <section class="rounded-md border overflow-hidden shadow-md">
        <div class="bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">
            <h1 class="text-3xl font-bold">{{$company->title}}</h1>
            <div class="mt-4">
                <ul class="flex">
                    <li class="flex flex-wrap mr-8">
                        Рейтинг:&nbsp;
                        <div class="flex text-yellow-500">
                            @if($comments->count())
                                @for($i=0; $i<5; $i++)
                                    @if(round($comments->avg('rating')) > $i)
                                        <svg class="h-5 w-5 mr-[2px]">
                                            <use xlink:href="/img/sprite.svg#star-full"></use>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 mr-[2px] text-gray-500">
                                            <use xlink:href="/img/sprite.svg#star-empty"></use>
                                        </svg>
                                    @endif
                                @endfor
                            @else
                                @for($i=0; $i<5; $i++)
                                    <svg class="h-5 w-5 text-gray-500 inline-block">
                                        <use xlink:href="/img/sprite.svg#star-empty"></use>
                                    </svg>
                                @endfor
                            @endif
                        </div>
                        &nbsp;(Отзывов:&nbsp;{{$comments->count()}})
                    </li>
                    <li class="inline-block">Просмотров: {{$company->views}}</li>
                </ul>
            </div>
        </div>
        <div class="sm:flex bg-gradient-to-b from-[#222222] via-black to-[#222222]">

            <div class="flex max-h-full">
                <div class="m-auto sm:pl-4">
                    <img class="rounded" src="
                @if($company->img)
                            /img/uploads/thumbnail/{{$company->img}}
                    @else
                            /img/logo.png
@endif"
                         alt="{{$company->title}}">
                </div>
            </div>


            <div class="py-4 px-4 w-full">

                <div class="flex items-center">
                    <svg class="text-red-600 w-5 h-5 min-w-[1.35rem] mr-2">
                        <use xlink:href="/img/sprite.svg#percent"></use>
                    </svg>
                    <span>Скидка: {{$company->discount}}</span>
                </div>

                @if($company->address)
                    <div class="mt-1 flex items-center">
                        <svg class="text-green-600 w-5 h-5 min-w-[1.35rem] mr-2">
                            <use xlink:href="/img/sprite.svg#location"></use>
                        </svg>
                        <span>Адрес: {{$company->address}}</span>
                    </div>
                @endif

                @if($company->working_hours)
                    <div class="mt-1 flex items-center">
                        <svg class="text-yellow-400 w-5 h-5 min-w-[1.35rem] mr-2">
                            <use xlink:href="/img/sprite.svg#time"></use>
                        </svg>
                        <span>Режим работы: {{$company->working_hours}}</span>
                    </div>
                @endif

                {{--                Телефон--}}
                @if($company->tel)
                    <div class="mt-1 flex items-center article">
                        <svg class="text-pink-700 w-5 h-5 min-w-[1.35rem] mr-2">
                            <use xlink:href="/img/sprite.svg#phone"></use>
                        </svg>
                        <span>Телефон: {!! $company->makeLinkTel() !!}</span>
                    </div>
                @endif

                {{--                Сайт--}}
                @if($company->site)
                    <div class="flex items-center mt-1 article">
                        {{--                    <i class="fas fa-globe text-blue-400 w-5 h-5 mr-2"></i>--}}
                        <svg class="text-blue-400 w-5 h-5 min-w-[1.35rem] mr-2">
                            <use xlink:href="/img/sprite.svg#globe"></use>
                        </svg>
                        <span>Сайт: <a href="{{$company->site}}" target="_blank">{{$company->site}}</a></span>
                    </div>
                @endif

                {{--                Соцсети--}}
                @if($company->facebook || $company->instagram || $company->vk || $company->youtube)
                    <div class="flex items-center relative mt-1">
                        {{--                    <i class="fas fa-users text-red-200 w-5 h-5 mr-2"></i>--}}
                        <svg class="text-red-200 w-5 h-5 min-w-[1.35rem] mr-2">
                            <use xlink:href="/img/sprite.svg#users"></use>
                        </svg>
                        <span>Соцсети:</span>

                        @if($company->instagram)
                            <a href="{{ $company->instagram }}" class="ml-2" target="_blank">
                                <svg class="h-7 w-7">
                                    <use xlink:href="/img/sprite.svg#instagram"></use>
                                </svg>
                            </a>
                        @endif
                        @if($company->facebook)
                            <a href="{{ $company->facebook }}" class="ml-2" target="_blank">
                                <svg class="h-7 w-7">
                                    <use xlink:href="/img/sprite.svg#facebook"></use>
                                </svg>
                            </a>
                        @endif
                        @if($company->vk)
                            <a href="{{ $company->vk }}" class="ml-2" target="_blank">
                                <svg class="h-7 w-7">
                                    <use xlink:href="/img/sprite.svg#vk"></use>
                                </svg>
                            </a>
                        @endif
                        @if($company->youtube)
                            <a href="{{ $company->youtube }}" class="ml-2" target="_blank">
                                <svg class="h-7 w-7">
                                    <use xlink:href="/img/sprite.svg#youtube"></use>
                                </svg>
                            </a>
                        @endif

                    </div>
                @endif

                <div class="flex items-center mt-1">
                    <svg class="text-yellow-200 w-5 h-5 min-w-[1.35rem] mr-2">
                        <use xlink:href="/img/sprite.svg#city"></use>
                    </svg>
                    <div class="flex items-center wrap">Город:
                        @foreach($company->cities as $city)
                            <a class="card__tag ml-2" href="{{route('showCity', $city)}}">{{$city->name}}</a>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center relative">
                    <svg class="text-yellow-600 w-5 h-5 min-w-[1.35rem] mr-2">
                        <use xlink:href="/img/sprite.svg#categories"></use>
                    </svg>
                    <div class="flex items-center wrap">Категория:
                        @foreach($company->categories as $category)
                            <a class="card__tag" href="{{route('showCategory', $category)}}">{{$category->name}}</a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    @auth()
        @if(auth()->user()->is_admin)
            @php($owner = \App\Models\User::where('id', $company->user_id)->first() ?? null)
            <section class="mt-4 rounded-md border overflow-hidden">
                <h2 class="p-4 bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">
                    Представитель организации
                </h2>
                <div class="p-4 bg-gradient-to-b from-[#222222] via-black to-[#222222]">
                    <a href="{{ route('partners.show', $owner) }}" class="border-dashed border-b hover:border-solid">
                        {{ $owner->name }}
                    </a>
                    :&nbsp;
                    <a href="tel:+{{ preg_replace("/[^0-9]/", '', $owner->tel) }}"
                       class="border-dashed border-b hover:border-solid">
                        {{ $owner->tel }}
                    </a>
                </div>
            </section>
        @endif
    @endauth
    @if($company->description)
        <section class="mt-4 rounded-md border overflow-hidden">
            <h2 class="p-4 bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">Описание</h2>
            <div class="p-4 bg-gradient-to-b from-[#222222] via-black to-[#222222]">
                {{$company->description}}
            </div>
        </section>
    @endif

    {{--    Карта--}}
    @if($company->coordinates)
        <section class="mt-4 rounded-md border overflow-hidden">
            <h2 class="p-4 bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">Карта</h2>
            <div class="h-[400px]" id="map"></div>
        </section>
        <script type="text/javascript">
            ymaps.ready(init);

            function init() {
                var myMap = new ymaps.Map("map", {
                    center: [{{ $company->coordinates }}],
                    zoom: 17
                });
                var myPlacemark = new ymaps.Placemark([{{ $company->coordinates }}], {
                    iconCaption: '',
                }, {
                    preset: 'islands#dotIcon',
                    iconColor: 'red',
                });
                myMap.geoObjects.add(myPlacemark);
                myMap.behaviors.disable('scrollZoom');
            }
        </script>
    @endif

    {{--    Отзывы--}}
    {{--    @include('components.comment.comments')--}}
    @livewire('comments', ['comments' => $comments, 'company_id' => $company->id])
@endsection

@section('scriptsFooter')
    @livewireScripts
@endsection
