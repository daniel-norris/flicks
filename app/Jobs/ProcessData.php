<?php

namespace App\Jobs;

use App\Import;
use App\Movie;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;

class ProcessData implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

            Movie::firstOrCreate(
                ['id' => $response['id']],
                [
                    'title' => $response['original_title'],
                    'backdrop_path' => $response['backdrop_path'],
                    'poster_path' => $response['poster_path'],
                    'budget' => $response['budget'],
                    'overview' => $response['overview'],
                    'popularity' => $response['popularity'],
                    'release_date' => $response['release_date'],
                    'revenue' => $response['revenue'],
                    'runtime' => $response['runtime'],
                    'status' => $response['status'],
                    'vote_average' => $response['vote_average'],
                    'vote_count' => $response['vote_count'],
                ]
            );
        });

        logger('completed batch data processing');
    }
}
