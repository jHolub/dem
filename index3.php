<?php

// MULTI-FLOW

$txt_file = file_get_contents('DMT.txt');
$matrixx = array();
$rows = explode("\n", $txt_file);
array_shift($rows);
for ($i = 0; $i < count($rows); $i++) {

    $row_data = explode("\t", $rows[$i]);
    $x = (int) ($i / 400);
    $y = $i % 400;
    $matrixx[$x][$y] = $row_data[2];
}

$matrix = array();

$catchment = array();
for ($i = 0; $i < 250; $i++) {
    for ($j = 0; $j < 250; $j++) {
        $matrix[$i][$j] = $matrixx[$i][$j];
        $catchment[$i][$j] = 0;
    }
}


$vahaMatrix = array();
$pow = pow(2, 0.5);

for ($x = 0; $x < count($matrix); $x++) {
    for ($y = 0; $y < count($matrix[$x]); $y++) {

        $vaha = 0;

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

            $val = $matrix[$x][$y];

            $sum = 0;
/// -1
            if ($val < $matrix[$x - 1][$y - 1]) {
                $vaha = $vahaMatrix[$x - 1][$y - 1];
                $dif = $matrix[$x - 1][$y - 1] - $val;
                $div = $dif / $pow;
                if ($catchment[$x - 1][$y - 1] == 0) {
                    $sum = $sum + ( 1 * ($div / $vaha));
                } else {
                    $g = $div / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y - 1] * $g));
                }
            }
            if ($val < $matrix[$x - 1][$y]) {
                $vaha = $vahaMatrix[$x - 1][$y];
                $dif = $matrix[$x - 1][$y] - $val;
                if ($catchment[$x - 1][$y] == 0) {
                    $sum = $sum + ( 1 * ($dif / $vaha));
                } else {
                    $g = $dif / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y] * $g));
                }
            }
            if ($val < $matrix[$x - 1][$y + 1]) {
                $vaha = $vahaMatrix[$x - 1][$y + 1];
                $dif = $matrix[$x - 1][$y + 1] - $val;
                $div = $dif / $pow;
                if ($catchment[$x - 1][$y + 1] == 0) {
                    $sum = $sum + ( 1 * ($div / $vaha));
                } else {
                    $g = $div / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x - 1][$y + 1] * $g));
                }
            }
// 0
            if ($val < $matrix[$x][$y - 1]) {
                $vaha = $vahaMatrix[$x][$y - 1];
                $dif = $matrix[$x][$y - 1] - $val;
                if ($catchment[$x][$y - 1] == 0) {
                    $sum = $sum + ( 1 * ($dif / $vaha));
                } else {
                    $g = $dif / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x][$y - 1] * $g));
                }
            }
            if ($val < $matrix[$x][$y + 1]) {
                $vaha = $vahaMatrix[$x][$y + 1];
                $dif = $matrix[$x][$y + 1] - $val;
                if ($catchment[$x][$y + 1] == 0) {
                    $sum = $sum + ( 1 * ($dif / $vaha));
                } else {
                    $g = $dif / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x][$y + 1] * $g));
                }
            }
// 1
            if ($val < $matrix[$x + 1][$y - 1]) {
                $vaha = $vahaMatrix[$x + 1][$y - 1];
                $dif = $matrix[$x + 1][$y - 1] - $val;
                $div = $dif / $pow;
                if ($catchment[$x + 1][$y - 1] == 0) {
                    $sum = $sum + ( 1 * ($div / $vaha));
                } else {
                    $g = $div / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y - 1] * $g));
                }
            }
            if ($val < $matrix[$x + 1][$y]) {
                $vaha = $vahaMatrix[$x + 1][$y];
                $dif = $matrix[$x + 1][$y] - $val;
                if ($catchment[$x + 1][$y] == 0) {
                    $sum = $sum + ( 1 * ($dif / $vaha));
                } else {
                    $g = $dif / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y] * $g));
                }
            }
            if ($val < $matrix[$x + 1][$y + 1]) {
                $vaha = $vahaMatrix[$x + 1][$y + 1];
                $dif = $matrix[$x + 1][$y + 1] - $val;
                $div = $dif / $pow;
                if ($catchment[$x + 1][$y + 1] == 0) {
                    $sum = $sum + ( 1 * ($div / $vaha));
                } else {
                    $g = $div / $vaha;
                    $sum = $sum + ((1 * $g) + ($catchment[$x + 1][$y + 1] * $g));
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
echo "<tr>" . $td . "</tr>";

for ($y = 200; $y >= 0; $y--) {

    echo "<tr><td>" . $y . "</td>";

    for ($x = 0; $x < 200; $x++) {

        // echo "<td style='border: 1px solid gray;'>" . $vizu[$slope[$x][$y]] . ' </td>';
        // echo "<td>" . $slope[$x][$y] . ' </td>';

        if ($catchment[$x][$y] == 0) {
            echo "<td>   </td>";
        } elseif ($catchment[$x][$y] > 0 && $catchment[$x][$y] < 10) {
            echo "<td style='background-color: Purple;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 10 && $catchment[$x][$y] < 30) {
            echo "<td style='background-color: blue;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 30 && $catchment[$x][$y] < 50) {
            echo "<td style='background-color:  Aqua;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 50 && $catchment[$x][$y] < 75) {
            echo "<td style='background-color: Lime;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 75 && $catchment[$x][$y] < 100) {
            echo "<td style='background-color: #7CFC00;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 100 && $catchment[$x][$y] < 150) {
            echo "<td style='background-color: yellow;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } elseif ($catchment[$x][$y] >= 150 && $catchment[$x][$y] < 250) {
            echo "<td style='background-color: orange;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        } else {
            echo "<td style='background-color: red;border: 1px solid gray;'>" . (int) $catchment[$x][$y] . "</td>";
        }
    }
    echo '</tr>';
}

$td = "<td>-</td>";
for ($x = 0; $x < count($matrix); $x++) {
    $td = $td . "<td>" . $x . "</td>";
}
echo "<tr>" . $td . "</tr>";

echo '</table>';

/*
 * 
 function vaha($x, $y, $matrix) {

    $vaha = 0;

    $pow = pow(2, 0.5);

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

    return $vaha;
}

 * 
 * 
 *  
      for ($i = -1; $i < 2; $i++) {
      for ($j = -1; $j < 2; $j++) {
      //diagonal
      if ((abs($i) + abs($j)) == 2) {
      if ($matrix[$x][$y] > $matrix[$x + $i][$y + $j]) {
      $dif = $matrix[$x][$y] - $matrix[$x + $i][$y + $j];
      $div = $dif / $pow;
      $vaha = $vaha + $div;
      }
      } else {
      if ($matrix[$x][$y] > $matrix[$x + $i][$y + $j]) {
      $vaha = $vaha + ( $matrix[$x][$y] - $matrix[$x + $i][$y + $j] );
      }
      }
      }
      }

              for ($i = -1; $i < 2; $i++) {
              for ($j = -1; $j < 2; $j++) {

              if ($val < $matrix[$x + $i][$y + $j]) {

              if ($catchment[$x + $i][$y + $j] == 0) {
              $vaha = vaha($x + $i, $y + $j, $matrix);

              if ((abs($i) + abs($j)) == 2) {
              $dif = $matrix[$x + $i][$y + $j] - $val;
              $div = $dif / $pow;
              $sum = $sum + ( 1 * ($div / $vaha));
              } else {
              $sum = $sum + ( 1 * (($matrix[$x + $i][$y + $j] - $val) / $vaha));
              }
              } else {
              $vaha = vaha($x + $i, $y + $j, $matrix);
              if ((abs($i) + abs($j)) == 2) {
              $dif = $matrix[$x + $i][$y + $j] - $val;
              $div = $dif / $pow;
              $g = ($div / $vaha);
              $sum = $sum + ((1 * $g) + ($catchment[$x + $i][$y + $j] * $g));
              } else {
              $dif = $matrix[$x + $i][$y + $j] - $val;
              $g = $dif / $vaha;
              $sum = $sum + ((1 * $g) + ($catchment[$x + $i][$y + $j] * $g));
              }
              }
              }
              }
              }
             */