@extends('layouts.app')

@section('content')
    <div class="mx-auto container flex mt-8">
        <div class="w-1/3 mr-6">
            <img src="{{ $imgBaseUrl . $movie['poster_path'] }}" alt="">
        </div>
        <div class="w-2/3 space-y-4">
            <div class="flex items-center">
                <h1 class="text-3xl font-bold">
                    {{ $movie['title']}}
                </h1>
                <p class="text-2xl font-thin ml-2">({{ substr($movie['release_date'], 0, 4) }})</p>
            </div>
            <div class="flex space-x-2 items-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <div class="flex items-center">
                    <p class="font-bold text-2xl">{{ $movie['vote_average'] }}<span class="font-thin"> / 10</span></p>
                    <p class="ml-2 font-thin">({{ number_format($movie['vote_count']) }})</p>
                </div>
            </div>
            <p>{{ $movie['overview'] }} </p>
        </div>
    </div>
@endsection