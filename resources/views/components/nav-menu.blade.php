<div class="flex flex-col-reverse justify-between w-full sm:block sm:relative">
    <div class="w-full text-gray-400 bg-black h-16">
        <div class="flex px-4 m-auto items-center justify-between flex-row md:px-6 lg:px-8">
            <div class="flex flex-row items-center justify-between p-4">
                <div class="flex transition-all duration-300">
                    <div :class="{'opacity-0 -ml-28': asideOpen}" class="header-logo opacity-0 -ml-28">
                        <a href="/">
                            <svg class="max-h-8 w-full mx-auto">
                                <use xlink:href="/img/sprite.svg#logo"></use>
                            </svg>
                        </a>
                    </div>
                    <button class="ml-2 focus:outline-none"
                            @click="asideOpen = !asideOpen">
                        <svg class="h-5 w-5">
                            <use xlink:href="/img/sprite.svg#menu"></use>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="flex-row text-gray-400 text-sm flex-grow flex items-center justify-end">
                <a class="flex px-4 py-2 rounded-lg hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none" href="/">
                    <span class="mr-1">
                        <svg class="h-5 w-5">
                            <use xlink:href="/img/sprite.svg#home"></use>
                        </svg>
                    </span>
                    <span class="hidden sm:block">Главная</span>
                </a>


                <a class="flex px-4 py-2 rounded-lg hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none" href="https://t.me/amsdiscountbot">
                    <span class="mr-1">
                        <svg  class="h-5 w-5">
                            <use xlink:href="/img/sprite.svg#telegram"></use>
                        </svg>
                    </span>
                    <span class="hidden sm:block">Телеграм бот</span>
                </a>

                <a class="flex px-4 py-2 rounded-lg hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none" href="tel:+79780820203">
                    <span class="mr-1">
                        <svg  class="h-5 w-5">
                            <use xlink:href="/img/sprite.svg#phone"></use>
                        </svg>
                    </span>
                    <span class="hidden sm:block">Позвонить нам</span>
                </a>

                <a class="flex px-4 py-2 rounded-lg hover:bg-red-800 focus:bg-red-800 focus:text-gray-300 hover:text-gray-300 md:mt-0 focus:outline-none"
                   @guest()
                   href="{{ route('login') }}"
                   @endguest
                   @auth()
                   href="{{ route('admin') }}"
                   @endauth>
                    <span class="mr-1">
                        <svg  class="h-5 w-5">
                            <use xlink:href="/img/sprite.svg#login"></use>
                        </svg>
                    </span>
                    <span class="hidden sm:block">
                        @guest()
                            Войти
                        @endguest
                        @auth()
                            Личный кабинет
                        @endauth
                    </span>
                </a>
            </nav>
        </div>
    </div>
</div>

