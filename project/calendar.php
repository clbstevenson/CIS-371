
<?php
date_default_timezone_set('America/New_York');
//echo "Query String:  " . $_SERVER['QUERY_STRING'] . "\n";

if(isset($_GET['day'])) {
    $day = $_GET['day'];
} else {
    $day = date('j');
}
if(isset($_GET['month'])) {
    $month= $_GET['month'];
} else {
    $month = date('n');
}
if(isset($_GET['year'])) {
    $year = $_GET['year'];
} else {
    $year = date('Y');
}

$new_time = mktime(null, null, null, $month, $day, $year);
//$new_date = date("M--d--Y",mktime(null, null, null, $month, $day, $year));
$new_date = date("M--d--Y",$new_time);
//echo "NEW DATE: ".$new_date;

$month_str = date('F', $new_time);
$current_month = date('n');

$days = date('t', $new_time);

$weeks = ceil($days / 7);
$startMonth = time() - (($day-1) * 24 * 60 * 60);
$startMonth = mktime(0,0,0,$month,1,$year);
$startOfMonth = date('w', $startMonth);
echo "Today is day $day; $month_str($month) starts on weekday $startOfMonth.\n";
echo "It has $days days and $weeks weeks";
$offset = $startOfMonth;
?>

<html>

<head>
    <link rel="stylesheet" href="calendar.css" type="text/css"/>
    <title>Monthly Calendar</title>
</head>


<body>
<h1> <?php echo "$month_str $year"?> </h1>

<?php
    $prev_month = $month-1;
    $prev_year = $year;
    if($prev_month == 0) {
        $prev_month = 12;
        $prev_year = $year-1;
    }
    echo "<a href='http://www.cis.gvsu.edu/~stevecal/PHP/calendar.php?month=$prev_month&day=$day&year=$prev_year'><<</a>"
?>

<table>
    <tr> <th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th>
        <th>Thursday</th><th>Friday</th><th>Saturday</th>
    </tr>
    <?php
        //$startDayOfWeek = date('w', mktime(null, null, null, $month, 2, $year));
        //print blank slots until the start day of the month
        for ($y = 0; $y < $startOfMonth; $y++) {
           echo "<td>-</td>"; 
        }
        $day_count = 0; // total days displayed
        $days_in_week_count = 1; // used for end-of-month 'blank' days
        for($day_x = 1; $day_x <= $days; $day_x++) {
            if($day_x == $day && $month == $current_month) {
                echo "<td id='TODAY'>$day_x</td>";
            } else {
                echo "<td>$day_x</td>";
            }

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
        if($days_in_week_count > 1 && $days_in_week_count <=7) {
            for($z = 1; $z <= (8-$days_in_week_count); $z++) {
                echo "<td>-</td>";
            }
        }
        echo "</tr>";
    ?>
</table>

<?php
    $next_month = $month+1;
    $next_year = $year;
    if($next_month == 13) {
        $next_month = 1;
        $next_year = $year + 1;
    }
    echo "<a href='http://www.cis.gvsu.edu/~stevecal/PHP/calendar.php?month=$next_month&day=$day&year=$next_year'>>></a>"
?>

</body>
</html>
