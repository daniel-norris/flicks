<?php

namespace Tests\Feature;

use App\Import;
use App\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function testRequestDataDump()
    {
        // disabled during development

        // $client = new \GuzzleHttp\Client();
        // $client->get('http://files.tmdb.org/p/exports/movie_ids_09_09_2020.json.gz', ['save_to' => storage_path() . '/app/imports/testFile.json.gz']);

        Storage::disk('local')->assertExists('/imports/testFile.json.gz');
        // Storage::disk('local')->delete('/imports/testFile.json.gz');
        // Storage::disk('local')->assertMissing('/imports/testFile.json');;
    }

    public function testUnzipDump()
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
     * @depends testParseFile
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
     * @depends testConvertFileToArray
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

        return $stack;
    }

    /**
     * @depends testSaveDataToImportModel
     */
    public function testSaveChunkedDataToImportModel(array $stack)
    {
        $collection = collect($stack);

        $chunks = $collection->take(100);

        $this->assertCount(100, $chunks);

        $chunks->map(function ($value) {
            $insert = new Import;
            $insert->id = $value->id ? $value->id : null;
            $insert->adult = $value->adult ? $value->adult : null;
            $insert->original_title = $value->original_title ? $value->original_title : null;
            $insert->popularity = $value->popularity ? $value->popularity : null;
            $insert->save();
        });

        // echo dump($chunks);



        // $secondValue = Import::find(2)->original_title;

        // $this->assertEquals("Ariel", $secondValue);

        // $all = Import::all();

        // echo dump($all);

        // $this->assertCount(100, $all);

        $all = Import::all();

        $ids = $all->map(function ($value) {
            return $value->id;
        });

        // dump($ids);

        $ids->map(function ($id) {
            $movie = Http::withToken(env('API_KEY'))
                ->get('https://api.themoviedb.org/3/movie/' . $id)
                ->json();

            dump($movie);

            $insert = new Movie;
            $insert->id = $movie->id;
            $insert->title = $movie
            $insert->save();
        });


        // echo dump(Movie::all());
    }

    /**
     * @depends testSaveChunkedDataToImportModel
     */
    public function testMakeChunkHttpRequests()
    {
        // $test = Import::find(2);
        // dump($test);
        // $this->assertEquals(2, $all->id);

        // echo dump($all);

        // foreach ($all->original as $each) {
        //     echo dump($each->name);
        // }
    }
}
