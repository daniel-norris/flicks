<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $moviesTopRated = Http::withToken('eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJkNmJiODhlNzAyYmQ5NzllYzNhNjQyZDIwYTM1NTgxOSIsInN1YiI6IjVmNmJjYWQyNjg4Y2QwMDAzNzI4ZDVjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VXJ7h4hlTVVq5PorrxGiOnPIG3N5_XRkH-XDxf6bNIg')
            ->post('https://api.themoviedb.org/3/movie/top_rated')
            ->json();

        $config = Http::withToken('eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJkNmJiODhlNzAyYmQ5NzllYzNhNjQyZDIwYTM1NTgxOSIsInN1YiI6IjVmNmJjYWQyNjg4Y2QwMDAzNzI4ZDVjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.VXJ7h4hlTVVq5PorrxGiOnPIG3N5_XRkH-XDxf6bNIg')
            ->get('https://api.themoviedb.org/3/configuration')
            ->json();

        dump($config);

        dump($moviesTopRated);

        $movies = collect($moviesTopRated['results'])->filter(function ($movie) {
            return $movie['adult'] === false
                && $movie['original_language'] === 'en'
                && $movie['popularity'] > 20;
        })->take(6);

        dump($movies);

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
        dump($id);

        return view('show', [
            'id' => $id,
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
