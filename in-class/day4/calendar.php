
<?php
date_default_timezone_set('America/New_York');
echo "Query String:  " . $_SERVER['QUERY_STRING'] . "\n";

$query = $_SERVER['QUERY_STRING'];


if(isset($query['day'])) {
    echo "D:". $query['day'];
    $day = $query['day'];
} else {
    $day = date('j');
}
echo "D:". $day;
if(isset($query['month'])) {
    $month= $query['month'];
} else {
    $month = date('M');
}
echo "M:". $month;
if(isset($query['year'])) {
    echo "Y:". $query['year'];
    $year = $query['year'];
} else {
    $year = date('Y');
}
echo "Y:". $year . "   ";

$new_date = mktime(null, null, null, $month, $day, $year);
echo "NEW DATE: ".$new_date;


parse_str($_SERVER['QUERY_STRING'], $query);
//echo "M:". $query['month'] . ";";
//echo "Y:". $query['year'];
//$custom_date = mktime(null, null, null, $_SERVER['QUERY_STRING']['month'], 2, $_SERVER['QUERY_STRING']['year']);
//echo "::".$custom_date."::";
//$month = date('M', $custom_date);
echo $month;
//$year = date('Y', $custom_date);
//$day = date('j', $custom_date);
//$days = date('t', $custom_date);
// original calendar date
$month = date('M');
$year = date('Y');
$day = date('j');
$days = date('t');

$weeks = ceil($days / 7);
$startMonth = time() - (($day-1) * 24 * 60 * 60);
$startOfMonth = date('w', $startMonth);
//echo "Today is $day; $month starts on $startOfMonth;\n";
//echo "$days days and  $weeks weeks";
$offset = $startOfMonth;
?>

<html>

<head>
    <link rel="stylesheet" href="calendar.css" type="text/css"/>
    <title>Monthly Calendar</title>
</head>


<body>
<h1> <?php echo "$month $year"?> </h1>

<table>
    <tr> <th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th>
        <th>Thursday</th><th>Friday</th><th>Saturday</th>
    </tr>
    <?php
        //$startDayOfWeek = date('w', mktime(null, null, null, $month, 2, $year));
        //echo $startDayOfWeek;
        //print blank slots until the start day of the month
        for ($y = 0; $y < $startOfMonth; $y++) {
           echo "<td>-</td>"; 
        }
        $day_count = 0; // total days displayed
        $days_in_week_count = 1; // used for end-of-month 'blank' days
        for($day_x = 1; $day_x <= $days; $day_x++) {
            echo "<td>$day_x</td>";

            // ignore first row if start on 6th day of week
            if($startOfMonth == 6) {
                echo "</tr>";
                if(($day_count+1)!=$days) {
                    //start a new row
                    echo "<tr>";
                }
                $startOfMonth = -1;
                $days_in_week_count = 0;
            }
            $day_count++;
            $days_in_week_count++;
            $startOfMonth++;
        }
        //display blanks for the end of the month
        if($days_in_week_count <=7) {
            for($z = 1; $z <= (8-$days_in_week_count); $z++) {
                echo "<td>-</td>";
            }
        }
        echo "</tr>";
        /*for ($weeknum = 0.0; $weeknum < $weeks; $weeknum++) {
            echo "<tr>";
            for ($x = 0.0; $x < 7; $x++) {
                // if there is an offset, then echo an empty slot
                $seven = 7;
                $daynum = 4;
                if($weeknum == 0 && $offset > 0) {
                   //  && $offset > 0) {
                    
                    echo "<td>$offset-</td>";
                    $offset--;
                } else {
                    echo "<td>";
                    //$daynum = $weeknum * 7;
                    //echo "$x:$weeknum:$daynum";
                    if ($startOfMonth == $x && $weeknum == 0) {
                        //echo date('j', $startMonth);
                        //echo date('j', $startOfMonth);
                    } else {
                        echo date('j', $startMonth + $x);
                        //echo "other";
                    }
                    //echo date('w:m-01-Y');
                    echo "</td>";    
                }
            }
            echo "</tr>";
        }
        */
    ?>
</table>

</body>
</html>
