<?php

namespace Tests\Feature;

use App\Import;
use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function testMovieDataDump()
    {
        $client = new \GuzzleHttp\Client();
        $client->get('http://files.tmdb.org/p/exports/movie_ids_09_09_2020.json.gz', ['save_to' => storage_path() . '/app/imports/testFile.json.gz']);

        Storage::disk('local')->assertExists('/imports/testFile.json.gz');
        // Storage::disk('local')->delete('/imports/testFile.json.gz');
        // Storage::disk('local')->assertMissing('/imports/testFile.json');;
    }

    public function testDumpUnzip()
    {
        $file_name = storage_path() . '/app/imports/testFile.json.gz';

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
        // Storage::disk('local')->delete('/imports/testFile.json');
        // Storage::disk('local')->assertMissing('/imports/testFile.json');
    }

    public function testParseFile()
    {
        $file = storage_path() . '/app/imports/testFile.json';

        $fopen = fopen($file, 'r');
        $fread = fread($fopen, filesize($file));

        fclose($fopen);

        $this->assertIsString($fread);

        return $fread;
    }

    /**
     * @depends testParseFileAndConvertToArray
     */
    public function testConvertFileToArray(string $fread)
    {
        $stack = [];

        $remove = "\n";
        $split = explode($remove, $fread);

        foreach ($split as $string) {
            $row = json_decode($string);
            array_push($stack, $row);
        }

        $this->assertIsArray($stack);
        $this->assertEquals($stack[0]->original_title, 'Blondie');

        return $stack;
    }

    /**
     * @depends testConvertDumpFileToArray
     */
    public function testSaveDataToImportModel(array $stack)
    {
        $import = new Import;

        $import->adult = 0;
        $import->id = $stack[0]->id;
        $import->original_title = $stack[0]->original_title;
        $import->popularity = $stack[0]->popularity;
        $import->save();

        $result = Import::find(3924);

        $this->assertEquals($result->adult, 0);
        $this->assertEquals($result->id, 3924);
        $this->assertEquals($result->original_title, "Blondie");
        $this->assertEquals($result->popularity, 2.744);
    }
}
