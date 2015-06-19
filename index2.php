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
for ($i = 0; $i < 200; $i++) {
    for ($j = 0; $j < 200; $j++) {
        $matrix[$i][$j] = $matrixx[$i][$j];
    }
}


$time_start = microtime(true);
// flow accumulation
//start iteration
$catchment = array();

for ($h = 0; $h < 200; $h++) {

    for ($x = 0; $x < count($matrix); $x++) {
        for ($y = 0; $y < count($matrix[$x]); $y++) {

            $val = $matrix[$x][$y];
            $sum = 0; 
            $r = 0;

            if ($val > $matrix[$x - 1][$y - 1]) {
                $sum = $sum + (($val - $matrix[$x - 1][$y - 1]) * 0.7);
                $r++;
            }
            if ($val > $matrix[$x - 1][$y]) {
                $sum = $sum + ($val - $matrix[$x - 1][$y]);
                $r++;
            }
            if ($val > $matrix[$x - 1][$y + 1]) {
                $sum = $sum + (($val - $matrix[$x - 1][$y + 1]) * 0.7);
                $r++;
            }
            if ($val > $matrix[$x][$y - 1]) {
                $sum = $sum + ($val - $matrix[$x][$y - 1]);
                $r++;
            }
            if ($val > $matrix[$x][$y + 1]) {
                $sum = $sum + ($val - $matrix[$x][$y + 1]);
                $r++;
            }
            if ($val > $matrix[$x + 1][$y - 1]) {
                $sum = $sum + (($val - $matrix[$x + 1][$y - 1]) * 0.7);
                $r++;
            }
            if ($val > $matrix[$x + 1][$y]) {
                $sum = $sum + ($val - $matrix[$x + 1][$y]);
                $r++;
            }
            if ($val > $matrix[$x + 1][$y + 1]) {
                $sum = $sum + (($val - $matrix[$x + 1][$y + 1]) * 0.7);
                $r++;
            }

            //$catchment[$x][$y] = $sum;
// -1
            if ($val > $matrix[$x - 1][$y - 1]) {

                $add = (($val - $matrix[$x - 1][$y - 1]) * 0.7 ) / $sum;

                $catchment[$x - 1][$y - 1] = $catchment[$x][$y] + $add;
            }

            if ($val > $matrix[$x - 1][$y]) {

                $add = ($val - $matrix[$x - 1][$y]) / $sum;

                $catchment[$x - 1][$y] = $catchment[$x][$y] + $add;
            }

            if ($val > $matrix[$x - 1][$y + 1]) {

                $add = (($val - $matrix[$x - 1][$y + 1] )* 0.7) / $sum;

                $catchment[$x - 1][$y + 1] = $catchment[$x][$y] + $add;
            }
            // 0    
            if ($val > $matrix[$x][$y - 1]) {

                $add = ($val - $matrix[$x][$y - 1]) / $sum;

                $catchment[$x][$y - 1] = $catchment[$x][$y] + $add;
            }

            if ($val > $matrix[$x][$y + 1]) {

                $add = ($val - $matrix[$x][$y + 1]) / $sum;

                $catchment[$x][$y + 1] = $catchment[$x][$y] + $add;
            }
//  +1      
            if ($val > $matrix[$x + 1][$y - 1]) {

                $add = (($val - $matrix[$x + 1][$y - 1])* 0.7) / $sum;

                $catchment[$x + 1][$y - 1] = $catchment[$x][$y] + $add;
            }

            if ($val > $matrix[$x + 1][$y]) {

                $add = ($val - $matrix[$x + 1][$y]) / $sum;

                $catchment[$x + 1][$y] = $catchment[$x][$y] + $add;
            }

            if ($val > $matrix[$x + 1][$y + 1]) {

                $add = (($val - $matrix[$x + 1][$y + 1])* 0.7) / $sum;

                $catchment[$x + 1][$y + 1] = $catchment[$x][$y] + $add;
            }
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
            echo "<td>" . $catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] > 0 && $catchment[$x][$y] < 10) {
        echo "<td style='background-color: Purple;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 10 && $catchment[$x][$y] < 30) {
            echo "<td style='background-color: blue;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 30 && $catchment[$x][$y] < 50) {
            echo "<td style='background-color:  Aqua;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 50 && $catchment[$x][$y] < 75) {
            echo "<td style='background-color: Lime;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 75 && $catchment[$x][$y] < 100) {
            echo "<td style='background-color: #7CFC00;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 100 && $catchment[$x][$y] < 150) {
            echo "<td style='background-color: yellow;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        } elseif ($catchment[$x][$y] >= 150 && $catchment[$x][$y] < 250) {
            echo "<td style='background-color: orange;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';
        }else{
            echo "<td style='background-color: red;border: 1px solid gray;'>" . (int)$catchment[$x][$y] . ' </td>';   
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