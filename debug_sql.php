<?php

$sql = file_get_contents('database/dump.sql');
$lines = file('database/dump.sql', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

echo "Total lines: " . count($lines) . "\n";
echo "File size: " . strlen($sql) . " bytes\n";
echo "\nFirst 10 lines:\n";

foreach (array_slice($lines, 0, 10) as $i => $line) {
    echo ($i + 1) . ": " . substr($line, 0, 100) . "\n";
}
