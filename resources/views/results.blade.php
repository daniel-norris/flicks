@extends('layouts.app')

@section('content')

    <div class="container mx-auto mt-8 flex justify-center">
        <div class="w-2/3 space-y-4">
            <h2 class="text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Results</h2>
            @foreach ($search['results'] as $result)
                @isset($result['title'])
                <div class="flex bg-gray-700 rounded shadow-md hover:opacity-75 text-gray-300">
                    <img class="w-32 rounded-l" src="{{ $imgBaseUrl . $result['poster_path'] }}" alt="">
                    <div class="w-1/4 p-4">
                        <h2 class="font-bold">{{ $result['title'] }}</h2>
                        <p class="mt-2 font-bold text-blue-500">{{ $result['vote_average'] }}<span class="font-thin"> / 10.0</span></p>
                        <p class="text-sm font-thin">({{ number_format($result['vote_count']) }})</p>
                    </div>
                    <div class="w-3/4 text-sm font-thin p-4">
                        <p>{{ $result['overview'] }}</p>
                    </div>
                </div>
                @endisset

            @endforeach
            @empty($search['results'])
                <p class="mt-8">There are no results. Try a new search.</p>
            @endempty
        </div>
    </div>

@endsection