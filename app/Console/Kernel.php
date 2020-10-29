<?php

namespace App\Console;


use App\Movie;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

use App\Console\Commands\Import;
use App\Jobs\ImportData;
use App\Jobs\ProcessData;
use App\Jobs\Unzip;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Stringable;

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

        // $schedule->job(new ImportData)->everyMinute();
        $schedule->job(new ProcessData)->everyMinute();
        $schedule->command('queue:work')->everyMinute()->onSuccess(function () {
            logger('The import on ' . date('H:i:s, j M y') . ' was successful');
        })->onFailure(function () {
            logger('The import on ' . date('H:i:s, j M y') . ' was unsuccessful');
        });
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
