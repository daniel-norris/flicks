@extends('layouts.app')

@section('content')

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