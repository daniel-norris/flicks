<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Import extends Model
{
    protected $guarded = [];

    public function unzip(string $path): string
    {
        $filename = $path;

        $bufferSize = 4096;
        $outputFilename = str_replace('.gz', '', $filename);

        $file = gzopen($filename, 'rb');
        $output = fopen($outputFilename, 'wb');

        while (!gzeof($file)) {
            fwrite($output, gzread($file, $bufferSize));
        }

        fclose($output);
        gzclose($file);

        // return Storage::url('/imports/testFile.json');
    }
}
