<?php
require 'vendor/autoload.php';
use Carbon\Carbon;
date_default_timezone_set('Asia/Jakarta');
$start = Carbon::createFromFormat('H:i','10:00','Asia/Jakarta');
$end = Carbon::createFromFormat('H:i','11:00','Asia/Jakarta');
echo $start->toDateTimeString() . ' ' . $start->getTimezone()->getName() . PHP_EOL;
echo $end->toDateTimeString() . ' ' . $end->getTimezone()->getName() . PHP_EOL;
echo 'diff=' . $end->diffInMinutes($start) . PHP_EOL;
echo 'diff abs=' . $end->diffInMinutes($start, true) . PHP_EOL;
echo 'diff false=' . $end->diffInMinutes($start, false) . PHP_EOL;
