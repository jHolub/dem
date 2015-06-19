<?php

$txt_file = file_get_contents('DMT.txt');
$matrix = array();
$rows = explode("\n", $txt_file);
array_shift($rows);

for ($i = 0; $i < count($rows); $i++) {

    $row_data = explode("\t", $rows[$i]);
    $x = (int) ($i / 400);
    $y = $i % 400;
    $matrix[$x][$y] = $row_data[2];
}



$slope = array();
//array(X,'↙', '←', '↖', '↑','↗' ,'→','↘','↓')
$vizu = array("X", "&#x2199", "&#x2190", "&#x2196", "&#x2191", "&#x2197", "&#x2192", "&#x2198", "&#x2193");

// index for flow direction
$key = array(
    array(1, 2, 3),
    array(8, 0, 4),
    array(7, 6, 5)
);

// flow direction
for ($x = 0; $x < count($matrix); $x++) {
    for ($y = 0; $y < count($matrix[$x]); $y++) {

        $slope[$x][$y] = $key[1][1];
        $min = $matrix[$x][$y];

        for ($i = -1; $i < 2; $i++) {
            for ($j = -1; $j < 2; $j++) {
// diagonal flow direct pow(.5)
                $dif = $matrix[$x][$y] - $matrix[$x + $i][$y + $j];
                if ((abs($i) + abs($j)) == 2) {
                    $div = $dif / pow(2, 0.5);
                    if ($div < $min) {
                        $min = $div;
                        $slope[$x][$y] = $key[$i + 1][$j + 1];
                    }
                } else {
                    if ($dif < $min) {
                        $min = $dif;
                        $slope[$x][$y] = $key[$i + 1][$j + 1];
                    }
                }
            }
        }
    }
}


$catchment = array();
$dd = array(
    array(5, 6, 7),
    array(4, 9, 8),
    array(3, 2, 1)
);

// determination catchment
for ($x = 0; $x < count($slope); $x++) {
    for ($y = 0; $y < count($slope[$x]); $y++) {

        $catchment[$x][$y] = 0;

        if ($slope[$x - 1][$y - 1] == $dd[0][0]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x - 1][$y] == $dd[0][1]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x - 1][$y + 1] == $dd[0][2]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x][$y - 1] == $dd[1][0]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x][$y + 1] == $dd[1][2]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x + 1][$y - 1] == $dd[2][0]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x + 1][$y] == $dd[2][1]) {
            $catchment[$x][$y] += 1;
        }
        if ($slope[$x + 1][$y + 1] == $dd[2][2]) {
            $catchment[$x][$y] += 1;
        }
    }
}

// flow accumulation
//start iteration
$iter = 0;
$change = 1;

while ($change != 0) {

    $change = 0;
    $iter++;

    for ($x = 0; $x < count($slope); $x++) {
        for ($y = 0; $y < count($slope[$x]); $y++) {

            if ($catchment[$x][$y] == 0)
                continue;

            $sum = 0;

            if ($slope[$x - 1][$y - 1] == $dd[0][0]) {
                if ($catchment[$x - 1][$y - 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x - 1][$y - 1];
                }
            }
            if ($slope[$x - 1][$y] == $dd[0][1]) {
                if ($catchment[$x - 1][$y] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x - 1][$y];
                }
            }
            if ($slope[$x - 1][$y + 1] == $dd[0][2]) {
                if ($catchment[$x - 1][$y + 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x - 1][$y + 1];
                }
            }
            if ($slope[$x][$y - 1] == $dd[1][0]) {
                if ($catchment[$x][$y - 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x][$y - 1];
                }
            }
            if ($slope[$x][$y + 1] == $dd[1][2]) {
                if ($catchment[$x][$y + 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x][$y + 1];
                }
            }
            if ($slope[$x + 1][$y - 1] == $dd[2][0]) {
                if ($catchment[$x + 1][$y - 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x + 1][$y - 1];
                }
            }
            if ($slope[$x + 1][$y] == $dd[2][1]) {
                if ($catchment[$x + 1][$y] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x + 1][$y];
                }
            }
            if ($slope[$x + 1][$y + 1] == $dd[2][2]) {
                if ($catchment[$x + 1][$y + 1] == 0) {
                    $sum = $sum + 1;
                } else {
                    $sum = $sum + 1 + $catchment[$x + 1][$y + 1];
                }
            }
            if ($catchment[$x][$y] != $sum) {
                $change++;
            }
            $catchment[$x][$y] = $sum;
        }
    }
}
