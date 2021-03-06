<?php

namespace App\Jobs;

use App\Import;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class ImportData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public $timeout = 1800;

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

        function datestamp(): string
        {
            $start = '00:00:01';

            // time UTC that source updates daily file exports of movies
            $upload   = '08:00:00';
            $now   = Carbon::now('UTC');
            $time  = $now->format('H:i:s');

            $result = "";

            if ($time <= $upload && $time >= $start) {
                $result = $now->subDay()->format('m_d_Y');
            } else {
                $result = $now->format('m_d_Y');
            };

            return $result;
        }

        function download(string $date): string
        {
            $client = new \GuzzleHttp\Client();
            $baseURL = 'http://files.tmdb.org';
            $filePath = storage_path() . '/app/imports/' . $date . '.json.gz';
            $client->get($baseURL . '/p/exports/movie_ids_' . $date . '.json.gz', ['sink' => $filePath]);

            logger('downloaded daily export gzip from ' . $baseURL);

            return $filePath;
        }

        function unzip(string $filePath): string
        {
            $bufferSize = 4096;
            $outputFilename = str_replace('.gz', '', $filePath);

            $file = gzopen($filePath, 'rb');
            $output = fopen($outputFilename, 'wb');

            while (!gzeof($file)) {
                fwrite($output, gzread($file, $bufferSize));
            }

            fclose($output);
            gzclose($file);

            $logName = str_replace('/var/www/html/storage/app/imports/', '', $filePath);
            logger('unzipped ' . $logName);
            return $outputFilename;
        }

        function parse(string $path = null): string
        {
            $date = date('m_d_Y');
            $file = $path ? $path : 'storage/app/imports/' . $date . '.json';

            $fileOpen = fopen($file, 'r');
            $fileRead = fread($fileOpen, filesize($file));

            fclose($fileOpen);

            $logName = str_replace('/var/www/html/storage/app/imports/', '', $file);
            logger('reading ' . $logName);
            return $fileRead;
        }

        function convert(string $text): array
        {
            $arrayWithObjects = [];

            $remove = "\n";
            $arrayWithStrings = explode($remove, $text);

            foreach ($arrayWithStrings as $string) {
                $row = json_decode($string);
                array_push($arrayWithObjects, $row);
            }

            $data = $arrayWithObjects;
            logger('converting text to array');
            return $data;
        }

        function insert(array $data): Collection
        {
            $count = count($data);
            $collection = collect($data);

            logger('inserting ' . number_format($count) . ' records into the database');

            $collection->map(function ($movie) {
                $new = Import::firstOrCreate(
                    ['id' => $movie->id],
                    [
                        'adult' => $movie->adult,
                        'original_title' => $movie->original_title,
                        'popularity' => $movie->popularity
                    ]
                );
            });

            $allImports = Import::all();

            logger('completed data import processing');
            return $allImports;
        }

        $date = datestamp();
        $filePath = download($date);
        $jsonPath = unzip($filePath);
        $fread = parse($jsonPath);
        $data = convert($fread);
        insert($data);

        logger('completed import data job');
    }
}
