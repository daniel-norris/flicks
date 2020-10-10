<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class ImportTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testMovieDataDump()
    {
        $client = new \GuzzleHttp\Client();
        $client->get('http://files.tmdb.org/p/exports/movie_ids_09_09_2020.json.gz', ['save_to' => storage_path() . '/app/imports/testFile.json.gz']);

        Storage::disk('local')->assertExists('/imports/testFile.json.gz');
        Storage::disk('local')->assertMissing('/imports/testFile.json');;
    }

    public function testMovieDataDumpUnzip()
    {
        $filename = Storage::get('/imports/testFile.json.gz');
        $file = gzfile($filename);
        print_r($filename);

        $lines = gzfile($filename);
        foreach ($lines as $line) {
            echo $line;
        }
    }
}
