<?php

$file = "movie_ids_09_01_2020.json";

$fopen = fopen($file, 'r');

$fread = fread($fopen, filesize($file));

fclose($fopen);

$remove = "\n";

$split = explode($remove, $fread);

$array = [];

foreach ($split as $string) {
    $row = json_decode($string);
    array_push($array, $row);
}

$ids = [];

foreach ($array as $key => $value) {
    array_push($ids, $value->id);
};

file_put_contents('convertdb.json', print_r($ids, true));
