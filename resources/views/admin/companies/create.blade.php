@extends('layouts.layout')
@section('title', '- Добавление организации')
@section('linksAside') <x-aside-links-admins />@endsection

@section('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?apikey=ff28f6ad-d2a1-40a6-b0fe-3ede2df62e11&lang=ru&load=package.standard" type="text/javascript"></script>
@endsection

@section('content')
{{--todo Сделать валидацию добавления организации--}}
<x-validation />

    <h1 class="mt-6 mb-4 text-2xl text-center">Добавление организации</h1>

    @include('components.company.create')

    <a
        class="block w-40 text-center mx-auto mt-4 py-2 px-4 space-x-2 rounded-md border hover:bg-red-800"
        href="{{ route('companies.index') }}"
    >
        Вернуться
    </a>
@endsection

@section('scriptsFooter')
    <script>
        ymaps.ready(init);


        function init() {
            const coordinates = document.querySelector('#coordinates');
            let myPlacemark;
            const myMap = new ymaps.Map('map', {
                    center: [45.15311552, 34.31881391],
                    zoom: 7
                }, {
                    searchControlProvider: 'yandex#search',
                });
            myMap.events.add('click', function (e) {
                let coords = e.get('coords');
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        coordinates.value = myPlacemark.geometry.getCoordinates();
                    });
                }
                coordinates.value = coords;
            });
        }
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {}, {
                preset: 'islands#dotIcon',
                iconColor:  'red',
                draggable: true
            });
        }
    </script>
@endsection
