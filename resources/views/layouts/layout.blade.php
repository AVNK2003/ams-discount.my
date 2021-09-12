<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Play&amp;display=swap" rel="stylesheet">
    @yield('scripts')
    <title>AMS Discount @yield('title')</title>
</head>
<body class="body">

<div x-data="setData()" x-cloak class="flex">
    <aside :class="{'-ml-56': !asideOpen}"
           class="aside flex flex-col top-0 left-0 w-56 bg-black fixed h-full z-30 transition-all duration-300">
    <a href="/" class="w-full p-4">
        <svg  class="max-h-10 w-28 mx-auto">
            <use xlink:href="/img/sprite.svg#logo"></use>
        </svg>
    </a>

    @yield('linksAside')


    </aside>
    <div @click="asideOpen=false" :class="{'hidden': !asideOpen}"
         class="bg-black md:hidden opacity-50 w-screen h-screen fixed"></div>
    <div :class="{ 'md:ml-56': asideOpen }"
         class="md:ml-56 flex flex-col justify-between w-full min-h-screen transition-all duration-300">
        <header class="header">

            <x-nav-menu/>

        </header>
        <main class="container flex-grow transition-all ease-in-out duration-300">
            @yield('content')
        </main>

        <footer class="footer"><span class="transition-all">©AMS DISCOUNT by <a class="link" href="http://automotosupport.ru">automotosupport.ru</a>
            2021</span>
        </footer>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
<script>
    function setData() {
        if (document.documentElement.clientWidth < 768)
            return { asideOpen: false };
        else
            return { asideOpen: true };
    }
</script>
@yield('scriptsFooter')
</body>
</html>
