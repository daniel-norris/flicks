@extends('layouts.app')

{{ dump($firstReviewContent)}}

@section('content')
    <div class="mx-auto container mt-8">
        <div class="flex h-64">
            <div class="w-64">
                <img class="h-full" src="{{ $imgBaseUrl . $movie['poster_path'] }}" alt="">
            </div>
            @isset($trailers)
                <div class="relative w-64 mr-6 hover:opacity-75">
                    <a href="{{ 'https://www.youtube.com/watch?v=' . $trailers['key'] }}">
                        <img class="h-full object-cover opacity-50" src="{{ $imgBaseUrl . $featureImage['file_path'] }}" alt="">
                        <svg class="h-1/2 absolute top-0 left-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                </div>
            @endisset
            <div class="w-2/3">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold">
                        {{ $movie['title']}}
                    </h1>
                    <p class="text-2xl font-thin ml-2">({{ substr($movie['release_date'], 0, 4) }})</p>
                </div>
                <div class="flex items-center">
                    <p class="text-xs mx-1">{{ $movie['runtime']}} min</p>
                    &vert;
                    <div class="flex text-xs mx-1 space-x-1">
                        @foreach ($movie['genres'] as $genre)
                            <p>{{ $genre['name'] }},</p>
                        @endforeach
                    </div>
                    &vert;
                    <p class="text-xs mx-1">{{ $movie['release_date'] }}</p>
                </div>
                <div class="flex space-x-2 items-center mt-4">
                    <div class="flex items-center">
                        <p class="font-bold text-2xl text-blue-500">{{ $movie['vote_average'] }}<span class="font-thin"> / 10.0</span></p>
                        <p class="ml-2 font-thin">({{ number_format($movie['vote_count']) }})</p>
                    </div>
                </div>
                <p class="mt-4">{{ $movie['overview'] }} </p>
            </div>
        </div>
        <h2 class="mt-8 text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Cast Members</h2>
        <div class="flex space-x-2 mt-4">
            @foreach ($cast as $castMember)
                <a href="#" class="bg-gray-900 w-1/6 rounded hover:opacity-75">
                    <img src="{{ $imgBaseUrl . $castMember['profile_path'] }}" alt="">
                    <p class="py-1 px-2 text-sm text-gray-600">{{ $castMember['name'] }}</p>
                </a>
            @endforeach
        </div>
        <h2 class="mt-8 text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Photos</h2>
        <div class="flex flex-wrap mt-4">
            @foreach ($images as $image)
                <a href="#" class="w-1/6 hover:opacity-75">
                    <img src="{{ $imgBaseUrl . $image['file_path'] }}" alt="">
                </a>
            @endforeach
        </div>
        <h2 class="mt-8 text-gray-300 font-bold text-lg uppercase leading-none border-l-4 border-blue-500 pl-4">Reviews</h2>
        <div class="flex flex-wrap mt-4">
            @isset($firstReview)
                <h1>by <span class="font-bold">{{ $firstReview['author'] }}</span></h1>
                <div>
                    @foreach ($firstReviewContent as $review)
                        <p class="mt-4 text-sm font-thin">{{ $review }}</p>
                    @endforeach
                </div>
            @endisset
            @empty($firstReview)
                <p>There are no reviews. Be the first and leave a review now.</p>
            @endempty
        </div>
    </div>
@endsection