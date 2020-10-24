<?php

namespace Tests\Feature;

use App\Import;
use App\Movie;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function testUploadDateStamp()
    {
        $start = '00:00:01';
        $upload   = '08:00:00';
        $now   = Carbon::now('UTC');
        $time  = $now->format('H:i:s');

        $result = "";

        if ($time <= $upload && $time >= $start) {
            $result = $now->subDay()->format('m_d_Y');
        } else {
            $result = $now->format('m_d_Y');
        };

        $this->assertEquals($result, "10_24_2020");

        return $result;
    }

    /**
     * @depends testUploadDateStamp
     */
    public function testRequestDataDump(string $datestamp)
    {
        $client = new \GuzzleHttp\Client();
        $client->get('http://files.tmdb.org/p/exports/movie_ids_' . $datestamp . '.json.gz', ['save_to' => storage_path() . '/app/imports/' . $datestamp . '.json.gz']);

        Storage::disk('local')->assertExists('/imports/' . $datestamp . '.json.gz');

        return $datestamp;
    }

    /**
     * @depends testRequestDataDump
     */
    public function testUnzipDump(string $datestamp)
    {
        $filename = storage_path() . '/app/imports/' . $datestamp . '.json.gz';

        $bufferSize = 4096;
        $outputFilename = str_replace('.gz', '', $filename);

        $file = gzopen($filename, 'rb');
        $output = fopen($outputFilename, 'wb');

        while (!gzeof($file)) {
            fwrite($output, gzread($file, $bufferSize));
        }

        fclose($output);
        gzclose($file);

        Storage::disk('local')->assertExists('/imports/' . $datestamp . '.json');

        return $datestamp;
    }

    /**
     * @depends testUnzipDump
     */
    public function testParseFile(string $datestamp)
    {
        $file = storage_path() . '/app/imports/' . $datestamp . '.json';

        $fileOpen = fopen($file, 'r');
        $fileRead = fread($fileOpen, filesize($file));

        fclose($fileOpen);

        $this->assertIsString($fileRead);

        return $fileRead;
    }

    /**
     * @depends testParseFile
     */
    public function testConvertFileToArray(string $fileRead)
    {
        $arrayWithObjects = [];

        $remove = "\n";
        $arrayWithStrings = explode($remove, $fileRead);

        foreach ($arrayWithStrings as $string) {
            $row = json_decode($string);
            array_push($arrayWithObjects, $row);
        }

        $this->assertIsArray($arrayWithObjects);
        $this->assertEquals($arrayWithObjects[0]->original_title, 'Blondie');

        $data = $arrayWithObjects;

        return $data;
    }

    /**
     * @depends testConvertFileToArray
     */
    public function testSaveDataToImportModel(array $data)
    {
        $movieRef = new Import;

        $movieRef->adult = 0;
        $movieRef->id = $data[0]->id;
        $movieRef->original_title = $data[0]->original_title;
        $movieRef->popularity = $data[0]->popularity;
        $movieRef->save();

        $result = Import::find(3924);

        $this->assertEquals($result->adult, 0);
        $this->assertEquals($result->id, 3924);
        $this->assertEquals($result->original_title, "Blondie");

        return $data;
    }

    /**
     * @depends testSaveDataToImportModel
     */
    public function testSaveChunkedDataToImportModel(array $data)
    {
        $collection = collect($data);
        $chunks = $collection->take(100);

        $this->assertCount(100, $chunks);

        $chunks->map(function ($movie) {
            $movieRef = new Import;
            $movieRef->id = $movie->id ? $movie->id : null;
            $movieRef->adult = $movie->adult ? $movie->adult : null;
            $movieRef->original_title = $movie->original_title ? $movie->original_title : null;
            $movieRef->popularity = $movie->popularity ? $movie->popularity : null;
            $movieRef->save();
        });

        $result = Import::find(2)->original_title;
        $this->assertEquals("Ariel", $result);

        $result = Import::all();
        $this->assertCount(100, $result);

        $allImports = Import::all();
        return $allImports;
    }

    /**
     * @depends testSaveChunkedDataToImportModel
     */
    public function testMakeChunkHttpRequests(Collection $allImports)
    {
        $ids = $allImports->map(function ($import) {
            return $import->id;
        });

        $ids->map(function ($id) {
            $response = Http::withToken(env('API_KEY'))
                ->get('https://api.themoviedb.org/3/movie/' . $id)
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

        $result = Movie::find(123);

        $this->assertEquals("The Lord of the Rings", $result->title);
        $this->assertEquals("1978-11-15", $result->release_date);
        $this->assertEquals("/jOuCWdh0BE6XPu2Vpjl08wDAeFz.jpg", $result->backdrop_path);
    }

    public function testCleanUpGzipAndJsonFiles()
    {
        Storage::delete('/imports/testFile.json.gz');
        Storage::disk('local')->assertMissing('/imports/testFile.json.gz');

        Storage::delete('/imports/testFile.json');
        Storage::disk('local')->assertMissing('/imports/testFile.json');
    }
}
