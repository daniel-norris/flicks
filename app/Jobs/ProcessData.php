<?php

namespace App\Jobs;

use App\Import;
use App\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ProcessData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $import;

    /**
     *  The number of times the job may be attempted.
     *
     *  @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        logger('started batch data processing');

        $import = Import::all();

        $import->map(function ($movie) {
            $response = Http::withToken(env('API_KEY'))
                ->get('https://api.themoviedb.org/3/movie/' . $movie->id)
                ->json();

            $movie = new Movie;
            $movie->id = $response['id'];
            $movie->title = $response['original_title'];
            $movie->backdrop_path = $response['backdrop_path'];
            $movie->poster_path = $response['poster_path'];
            $movie->budget = $response['budget'];
            $movie->overview = $response['overview'];
            $movie->popularity = $response['popularity'];
            $movie->release_date = $response['release_date'];
            $movie->revenue = $response['revenue'];
            $movie->runtime = $response['runtime'];
            $movie->status = $response['status'];
            $movie->vote_average = $response['vote_average'];
            $movie->vote_count = $response['vote_count'];
            $movie->save();
        });

        logger('completed batch data processing');
    }
}
