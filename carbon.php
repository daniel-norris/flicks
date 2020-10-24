<?php

use Carbon\Carbon;

$time = Carbon::now(); // Current time
$start = Carbon::create($time->year, $time->month, $time->day); //set time to 10:00
$end = Carbon::create($time->year, $time->month, $time->day, 9, 0, 0); //set time to 18:00

if ($start > $end) {
    echo "yes";
};
