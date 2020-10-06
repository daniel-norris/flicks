<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moviesTopRated = Http::withToken(env('API_KEY'))
            ->post('https://api.themoviedb.org/3/movie/top_rated')
            ->json();

        $config = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/configuration')
            ->json();

        $movies = collect($moviesTopRated['results'])->filter(function ($movie) {
            return $movie['adult'] === false
                && $movie['original_language'] === 'en'
                && $movie['popularity'] > 20;
        })->take(6);

        return view('index', [
            'movies' => $movies,
            'imgBaseUrl' => $config['images']['base_url'] . $config['images']['backdrop_sizes'][3],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $movieDetails = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/movie/' . $id)
            ->json();

        $movieCredits = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '/credits')
            ->json();

        $movieVideos = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '/videos')
            ->json();

        $movieImages = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '/images')
            ->json();

        $movieReviews = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/movie/' . $id . '/reviews')
            ->json();

        $config = Http::withToken(env('API_KEY'))
            ->get('https://api.themoviedb.org/3/configuration')
            ->json();

        $trailers = collect($movieVideos['results'])->map(function ($video) {
            return $video;
        })->filter(function ($trailer) {
            return $trailer['type'] === 'Trailer';
        })->first();

        $highestRatedImage = collect($movieImages['backdrops'])->where('vote_count', '>', 1)->first();

        return view('show', [
            'movie' => $movieDetails,
            'cast' => collect($movieCredits['cast'])->take(6),
            'trailers' => $trailers,
            'images' => collect($movieImages['backdrops'])->take(6),
            'featureImage' => $highestRatedImage,
            'firstReview' => collect($movieReviews['results'])->first(),
            'firstReviewContent' => Str::of($movieReviews['results'][0]['content'])->explode("\r\n"),
            'imgBaseUrl' => $config['images']['base_url'] . $config['images']['poster_sizes'][5],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
