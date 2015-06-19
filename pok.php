<?php

$time_start = microtime(true);

$matrix = array();
$inter = 0;
while ($inter < 10) {
    for ($i = 1; $i < 1000; $i++) {
        for ($j = 1; $j < 1000; $j++) {
            $matrix[$i][$j] = pow($i * $j, 0.5);
            pow($matrix[$i][$j], 0.5);
        }
    }
    $inter++;
}
echo 'Total execution time in seconds: ' . (microtime(true) - $time_start) . "<br>";

