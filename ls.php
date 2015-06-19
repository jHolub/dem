<?php

//
//  Y
//  /\
//  | 5 9 2 7 
//  | 3 8 9 1
//  | 2 7 3 8
//  | 2 5 2 5
//  -------------------> X
//[0,0]

$txt_file = file_get_contents('propustnost.txt');
$matrix = array();
$rows = explode("\n", $txt_file);
//array_shift($rows);
/*
  0 ncols         203
  1 nrows         161
  2 xllcorner     -784767,48311452
  3 yllcorner     -1151034,025189
  4 cellsize      5
  5 NODATA_value  -9999
 */

$NODATA = -9999;


$y = 0;
for ($i = (count($rows) - 1); $i > 5; $i--) {

    $row_data = explode(" ", $rows[$i]);

    for ($j = 0; $j < count($row_data); $j++) {

        $row_data[$j] = strtr($row_data[$j], ',', '.');
    }

    $matrix[$y] = $row_data;
    $y++;
}



$catchment = array();
for ($i = 0; $i < count($matrix); $i++) {
    for ($j = 0; $j < count($matrix[$i]); $j++) {
        $catchment[$i][$j] = 0;
    }
}


$vahaMatrix = array();
$pow = pow(2, 0.5);

for ($x = 0; $x < count($matrix); $x++) {
    for ($y = 0; $y < count($matrix[$x]); $y++) {

        $vaha = 0;

        if ($matrix[$x][$y] == $NODATA) {
            continue;
        }

        if ($matrix[$x][$y] > $matrix[$x - 1][$y - 1]) {
            $dif = $matrix[$x][$y] - $matrix[$x - 1][$y - 1];
            $div = $dif / $pow;
            $vaha = $vaha + $div;
        }
        if ($matrix[$x][$y] > $matrix[$x - 1][$y]) {
            $vaha = $vaha + ( $matrix[$x][$y] - $matrix[$x - 1][$y] );
        }
        if ($matrix[$x][$y] > $matrix[$x - 1][$y + 1]) {
            $dif = $matrix[$x][$y] - $matrix[$x - 1][$y + 1];
            $div = $dif / $pow;
            $vaha = $vaha + $div;
        }


        if ($matrix[$x][$y] > $matrix[$x][$y - 1]) {
            $vaha = $vaha + ( $matrix[$x][$y] - $matrix[$x][$y - 1] );
        }
        if ($matrix[$x][$y] > $matrix[$x][$y + 1]) {
            $vaha = $vaha + ( $matrix[$x][$y] - $matrix[$x][$y + 1] );
        }


        if ($matrix[$x][$y] > $matrix[$x + 1][$y - 1]) {
            $dif = $matrix[$x][$y] - $matrix[$x + 1][$y - 1];
            $div = $dif / $pow;
            $vaha = $vaha + $div;
        }
        if ($matrix[$x][$y] > $matrix[$x + 1][$y]) {
            $vaha = $vaha + ( $matrix[$x][$y] - $matrix[$x + 1][$y] );
        }
        if ($matrix[$x][$y] > $matrix[$x + 1][$y + 1]) {
            $dif = $matrix[$x][$y] - $matrix[$x + 1][$y + 1];
            $div = $dif / $pow;
            $vaha = $vaha + $div;
        }

        $vahaMatrix[$x][$y] = $vaha;
    }
}

$time_start = microtime(true);

$change = 1;

while ($change != 0) {

    $change = 0;

    for ($x = 0; $x < count($matrix); $x++) {
        for ($y = 0; $y < count($matrix[$x]); $y++) {

            if ($matrix[$x][$y] == $NODATA) {
                continue;
            }

            $val = $matrix[$x][$y];

            $sum = 0;
/// -1
            if ($val < $matrix[$x - 1][$y - 1]) {
                $vaha = $vahaMatrix[$x - 1][$y - 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x - 1][$y - 1] - $val;
                    $div = $dif / $pow;
                    if ($catchment[$x - 1][$y - 1] == 0) {
                        $sum = $sum + ( 1 * ($div / $vaha));
                    } else {
                        $g = $div / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y - 1] * $g));
                    }
                }
            }
            if ($val < $matrix[$x - 1][$y]) {
                $vaha = $vahaMatrix[$x - 1][$y];
                if ($vaha != 0) {
                    $dif = $matrix[$x - 1][$y] - $val;
                    if ($catchment[$x - 1][$y] == 0) {
                        $sum = $sum + ( 1 * ($dif / $vaha));
                    } else {
                        $g = $dif / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y] * $g));
                    }
                }
            }
            if ($val < $matrix[$x - 1][$y + 1]) {
                $vaha = $vahaMatrix[$x - 1][$y + 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x - 1][$y + 1] - $val;
                    $div = $dif / $pow;
                    if ($catchment[$x - 1][$y + 1] == 0) {
                        $sum = $sum + ( 1 * ($div / $vaha));
                    } else {
                        $g = $div / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y + 1] * $g));
                    }
                }
            }
// 0
            if ($val < $matrix[$x][$y - 1]) {
                $vaha = $vahaMatrix[$x][$y - 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x][$y - 1] - $val;
                    if ($catchment[$x][$y - 1] == 0) {
                        $sum = $sum + ( 1 * ($dif / $vaha));
                    } else {
                        $g = $dif / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x][$y - 1] * $g));
                    }
                }
            }
            if ($val < $matrix[$x][$y + 1]) {
                $vaha = $vahaMatrix[$x][$y + 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x][$y + 1] - $val;
                    if ($catchment[$x][$y + 1] == 0) {
                        $sum = $sum + ( 1 * ($dif / $vaha));
                    } else {
                        $g = $dif / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x][$y + 1] * $g));
                    }
                }
            }
// 1
            if ($val < $matrix[$x + 1][$y - 1]) {
                $vaha = $vahaMatrix[$x + 1][$y - 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x + 1][$y - 1] - $val;
                    $div = $dif / $pow;
                    if ($catchment[$x + 1][$y - 1] == 0) {
                        $sum = $sum + ( 1 * ($div / $vaha));
                    } else {
                        $g = $div / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y - 1] * $g));
                    }
                }
            }
            if ($val < $matrix[$x + 1][$y]) {
                $vaha = $vahaMatrix[$x + 1][$y];
                if ($vaha != 0) {
                    $dif = $matrix[$x + 1][$y] - $val;
                    if ($catchment[$x + 1][$y] == 0) {
                        $sum = $sum + ( 1 * ($dif / $vaha));
                    } else {
                        $g = $dif / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y] * $g));
                    }
                }
            }
            if ($val < $matrix[$x + 1][$y + 1]) {
                $vaha = $vahaMatrix[$x + 1][$y + 1];
                if ($vaha != 0) {
                    $dif = $matrix[$x + 1][$y + 1] - $val;
                    $div = $dif / $pow;
                    if ($catchment[$x + 1][$y + 1] == 0) {
                        $sum = $sum + ( 1 * ($div / $vaha));
                    } else {
                        $g = $div / $vaha;
                        $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y + 1] * $g));
                    }
                }
            }

            if ($catchment[$x][$y] != $sum) {
                $change++;
            }
            $catchment[$x][$y] = $sum;
        }
    }
}



echo 'Total execution time in seconds: ' . (microtime(true) - $time_start);






echo "<table style='width: 8000px;border-collapse: collapse'>";
$td = "<td>-</td>";
for ($x = 0; $x < count($matrix); $x++) {
    $td = $td . "<td>" . $x . "</td>";
}
"<tr>" . $td . "</tr>";

for ($y = 300; $y >= 0; $y--) {

    "<tr><td>" . $y . "</td>";

    for ($x = 0; $x < 300; $x++) {

        //  echo "<td style='border: 1px solid gray;'>" . $matrix[$y][$x] . ' </td>';
        //(int) $catchment[$y][$x]
        if ($catchment[$y][$x] == 0) {
            echo "<td>   </td>";
        } elseif ($catchment[$y][$x] > 0 && $catchment[$y][$x] < 10) {
            echo "<td style='background-color: Purple;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 10 && $catchment[$y][$x] < 30) {
            echo "<td style='background-color: blue;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 30 && $catchment[$y][$x] < 50) {
            echo "<td style='background-color:  Aqua;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 50 && $catchment[$y][$x] < 75) {
            echo "<td style='background-color: Lime;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 75 && $catchment[$y][$x] < 100) {
            echo "<td style='background-color: #7CFC00;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 100 && $catchment[$y][$x] < 150) {
            echo "<td style='background-color: yellow;border: 1px solid gray;'>" . "O" . "</td>";
        } elseif ($catchment[$y][$x] >= 150 && $catchment[$y][$x] < 250) {
            echo "<td style='background-color: orange;border: 1px solid gray;'>" . "O" . "</td>";
        } else {
            echo "<td style='background-color: red;border: 1px solid gray;'>" . "O" . "</td>";
        }
    }
    echo '</tr>';
}

$td = "<td>-</td>";
for ($x = 0; $x < count($matrix); $x++) {
    $td = $td . "<td>" . $x . "</td>";
}
"<tr>" . $td . "</tr>";

echo '</table>';
