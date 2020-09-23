@extends('layouts.app')

@section('content')
    <div class="mx-auto container mt-8">

        <h1 class="text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Highest Sparkle Rating</h1>

        <div class="grid grid-cols-6 grid-rows-2 gap-4 mt-8">
            @foreach (range(1, 12) as $movie)
            <div class="flex-col">
                <div class="bg-gray-400 rounded w-full h-64"></div>
                <div class="flex p-2 space-x-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>92%</span>
                </div>
                <p class="p-2">Avatar</p>
            </div>
            @endforeach
        </div>
    </div>
@endsection