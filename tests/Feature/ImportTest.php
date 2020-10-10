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
        // Storage::disk('local')->delete('/imports/testFile.json.gz');
        Storage::disk('local')->assertMissing('/imports/testFile.json');;
    }

    public function testDumpUnzip()
    {
        $file_name = 'storage/app/imports/testFile.json.gz';

        $buffer_size = 4096;
        $out_file_name = str_replace('.gz', '', $file_name);

        $file = gzopen($file_name, 'rb');
        $out_file = fopen($out_file_name, 'wb');

        while (!gzeof($file)) {
            fwrite($out_file, gzread($file, $buffer_size));
        }

        fclose($out_file);
        gzclose($file);

        Storage::disk('local')->assertExists('/imports/testFile.json');
        Storage::disk('local')->delete('/imports/testFile.json');
        Storage::disk('local')->assertMissing('/imports/testFile.json');
    }
}
