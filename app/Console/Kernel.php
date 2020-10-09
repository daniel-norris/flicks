<?php

namespace App\Console;

use App\Movie;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $movie = Http::withToken(env('API_KEY'))
                ->get('https://api.themoviedb.org/3/movie/556574')
                ->json();

            $newMovie = new Movie();
            $newMovie->title = $movie['title'];
            $newMovie->backdrop_path = $movie['backdrop_path'];
            $newMovie->poster_path = $movie['poster_path'];
            $newMovie->budget = $movie['budget'];
            $newMovie->overview = $movie['overview'];
            $newMovie->popularity = $movie['popularity'];
            $newMovie->release_date = $movie['release_date'];
            $newMovie->revenue = $movie['revenue'];
            $newMovie->runtime = $movie['runtime'];
            $newMovie->status = $movie['status'];
            $newMovie->vote_average = $movie['vote_average'];
            $newMovie->vote_count = $movie['vote_count'];
            $newMovie->save();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
