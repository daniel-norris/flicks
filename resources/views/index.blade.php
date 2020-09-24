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
                    <div class="flex space-x-2 items-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        <p class="font-bold">{{ $movie['vote_average'] }}<span class="ml-1 font-thin">({{ $movie['vote_count'] }})</span></p>
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