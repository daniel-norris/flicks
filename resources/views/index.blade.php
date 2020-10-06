@extends('layouts.app')

@section('content')

    <div class="relative h-screen flex justify-center items-center">
        <img class="w-full h-full opacity-50 shadow-md" src="{{ asset('images/landing.jpg') }}" alt="">
        <div class="absolute inset-auto">
            <h1 class="text-blue-500 font-bold text-3xl">Search across 1000's of movies and tv shows.</h1>
            <form class="mt-8" method="POST" action="{{ route('search') }}">
                @csrf
                <label for=""></label>
                <input class="shadow appearance-none border rounded w-3/4 py-2 px-3 text-gray-700 leading-light focus:outline-none focus:shadow-outline" placeholder="Search now..." name="search" type="text">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="mx-auto container mt-8">
        <h2 class="text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Highest Rated</h2>
        <div class="grid grid-cols-6 grid-rows-2 gap-4 mt-8">
            @foreach ($movies as $movie)
            <div class="flex-col shadow-md bg-gray-700 rounded">
                <a href="{{ route('movie.show', $movie['id']) }}">
                    <img src="{{ $imgBaseUrl . ($movie['poster_path'])}}" class="bg-gray-400 rounded-t w-full h-80 hover:opacity-75" />
                </a>
                <div class="p-4">
                    <div class="flex items-center">
                        <p class="font-bold text-blue-500">{{ $movie['vote_average'] }}<span class="font-thin"> / 10.0</span></p>
                        <p class="ml-2 font-thin">({{ number_format($movie['vote_count']) }})</p>
                    </div>
                    <a class="hover:text-gray-400" href="{{ route('movie.show', $movie['id']) }}">
                        <p>{{ $movie['title'] }} </p>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection