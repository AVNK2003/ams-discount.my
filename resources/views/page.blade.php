@extends('layouts.layout')
@section('title', '- '.$page->title)
@section('linksAside') <x-aside-links-all />@endsection
@section('content')

<section class="mt-4 rounded-md border overflow-hidden">
    <h1 class="p-4 bg-gradient-to-b from-[#100000] via-[#300000] to-[#200000] px-6 py-4 border-b">{{ $page->title }}</h1>
    <div class="p-4 bg-gradient-to-b from-[#222222] via-black to-[#222222]">
        <div class="article">
            {!! $page->text !!}
        </div>
    </div>
</section>

@endsection
