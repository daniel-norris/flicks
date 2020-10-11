<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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

        echo "\e[0;30;42mCreated: " . str_replace('storage/app/imports/', '', $outputFilename) . "\e[0m";
        return $outputFilename;
    }

    public function parseFile(string $path = null): string
    {
        $date = date('m_d_Y');
        $file = $path ? $path : 'storage/app/imports/movie_ids_' . $date . '.json.gz';

        $fileOpen = fopen($file, 'r');
        $fileRead = fread($fileOpen, filesize($file));

        fclose($fileOpen);

        return $fileRead;
    }
}
