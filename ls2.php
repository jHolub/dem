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
$y = 0;
for ($i = (count($rows) - 1); $i > 5; $i--) {

    $row_data = explode(" ", $rows[$i]);
    
    for($j = 0; $j < count($row_data); $j++){
        
        $row_data[$j] = strtr($row_data[$j],',','.');  
    }
    
    $matrix[$y] = $row_data;
    $y++;
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

        
      if($matrix[$y][$x] < 460 && $matrix[$y][$x] > 0){  
      echo "<td style='background-color:  green;border: 1px solid gray;'>" . "O" . ' </td>';
      }elseif($matrix[$y][$x] > 460 && $matrix[$y][$x] < 470){
           echo "<td style='background-color:  blue; border: 1px solid gray;'>" . "O" . ' </td>';
      }elseif($matrix[$y][$x] > 470 && $matrix[$y][$x] < 480){
          echo "<td style='background-color:  red; border: 1px solid gray;'>" . "O" . ' </td>'; 
      }elseif($matrix[$y][$x] > 480 && $matrix[$y][$x] < 490){
         echo "<td style=' background-color:  pink; border: 1px solid gray;'>" . "O" . ' </td>';  
      }elseif($matrix[$y][$x] > 490){
        echo "<td style='background-color:  yellow; border: 1px solid gray;'>" . "O" . ' </td>';   
      }else{
         echo "<td style=' border: 1px solid gray;'>" . "O" . ' </td>';     
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