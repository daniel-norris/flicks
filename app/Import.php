<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    protected $guarded = [];

    public function unzipGz(string $date = null): string
    {
        $dateFormat = $date ?  $date : date('m_d_Y');

        $filename = 'storage/app/imports/movie_ids_' . $date . '.json.gz';

        $bufferSize = 4096;
        $outputFilename = str_replace('.gz', '', $filename);

        $file = gzopen($filename, 'rb');
        $output = fopen($outputFilename, 'wb');

        while (!gzeof($file)) {
            fwrite($output, gzread($file, $bufferSize));
        }

        fclose($output);
        gzclose($file);

        echo "\e[0;30;42mExtracting... " . str_replace('storage/app/imports/', '', $outputFilename) . "\e[0m\n";
        return $outputFilename;
    }

    public function parseFile(string $path = null): string
    {
        $date = date('m_d_Y');
        $file = $path ? $path : 'storage/app/imports/movie_ids_' . $date . '.json.gz';

        $fileOpen = fopen($file, 'r');
        $fileRead = fread($fileOpen, filesize($file));

        fclose($fileOpen);
        echo "\e[0;30;42mReading... " . str_replace('storage/app/imports/', '', $file) . "\e[0m\n";
        return $fileRead;
    }

    public function convertText(string $text): array
    {
        $arrayWithObjects = [];

        $remove = "\n";
        $arrayWithStrings = explode($remove, $text);

        foreach ($arrayWithStrings as $string) {
            $row = json_decode($string);
            array_push($arrayWithObjects, $row);
        }

        $data = $arrayWithObjects;
        echo "\e[0;30;42mConverting text file to array... \e[0m\n";
        return $data;
    }

    public function saveAllData(array $data): Collection
    {
        $collection = collect($data);
        
        echo "\e[0;30;42mConverting text file to array... \e[0m\n";

        $collection->map(function ($movie) {
            $movieRef = new Import;
            $movieRef->id = $movie->id ? $movie->id : null;
            $movieRef->adult = $movie->adult ? $movie->adult : null;
            $movieRef->original_title = $movie->original_title ? $movie->original_title : null;
            $movieRef->popularity = $movie->popularity ? $movie->popularity : null;
            $movieRef->save();
        });

        $allImports = Import::all();
        echo "\e[0;30;42mConverting text file to array... \e[0m\n";
        return $allImports;
    }
}
