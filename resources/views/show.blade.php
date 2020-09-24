@extends('layouts.app')

@section('content')
    <div class="mx-auto container flex mt-8">
        <div class="w-1/3 mr-6">
            <img src="{{ $imgBaseUrl . $movie['poster_path'] }}" alt="">
        </div>
        <div class="w-2/3">
           {{ $movie['title']}}
        </div>
    </div>
@endsection