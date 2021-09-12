<nav class="flex-1 text-sm text-gray-400 overflow-hidden hover:overflow-y-auto">
    <ul class="py-2 px-4 overflow-hidden">
        <li>
            <a href="{{ route('companies.index') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#org"></use>
                    </svg>
                </span>
                <span>Организации</span>
            </a>
        </li>

        @if(auth()->user()->is_admin)
        <li>
            <a href="{{ route('partners.index') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none" >
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#users"></use>
                    </svg>
                </span>
                <span>Партнеры</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admins') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none" >
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#bell"></use>
                    </svg>
                </span>
                <span>Админы</span>
            </a>
        </li>

        <li>
            <a href="{{ route('cities.index') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#city"></use>
                    </svg>
                </span>
                <span>Города</span>
            </a>
        </li>

        <li>
            <a href="{{ route('categories.index') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#categories"></use>
                    </svg>
                </span>
                <span>Категории</span>
            </a>
        </li>

        <li>
            <a href="{{ route('pages.index') }}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#page"></use>
                    </svg>
                </span>
                <span>Страницы</span>
            </a>
        </li>

        <li>
            <a href="{{route('comments.index')}}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#reviews"></use>
                    </svg>
                </span>
                <span>Отзывы</span>
            </a>
        </li>

        <li>
            <a href="{{route('bot')}}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#bot"></use>
                    </svg>
                </span>
                <span>Бот</span>
            </a>
        </li>
        @endif

{{--        <li>
            <a href="#" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#messages"></use>
                    </svg>
                </span>
                <span>Сообщения</span>
            </a>
        </li>--}}

        <!-- Sidebar Links... -->
    </ul>
</nav>
<!-- Sidebar footer -->
<div class="flex-shrink-0 m-4 text-sm text-gray-400">
    <ul>
        <li>
            <a href="{{route('profile')}}" class="flex items-center p-2 space-x-2 rounded-md hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#login"></use>
                    </svg>
                </span>
                <span>Профиль</span>
            </a>
        </li>
        <li>
            <form action="{{route('logout')}}" method="post">
                @csrf
                <button type="submit" class="flex w-full items-center p-2 space-x-2 rounded-md focus:outline-none hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none">
                    <span>
                    <svg  class="h-5 w-5">
                        <use xlink:href="/img/sprite.svg#exit"></use>
                    </svg>
                </span>
                    <span>Выход</span>
                </button>
            </form>
        </li>
    </ul>
</div>
