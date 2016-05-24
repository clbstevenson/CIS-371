
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

// if selected a theme, set the cookie
if(isset($_POST['myTheme'])) {
    $theme = $_POST['myTheme'];
    setcookie('cookietheme', $theme, time() + 5);
    echo "CookieTheme: $theme for 5 seconds";
} else {
    // check to see if a cookie has been set with key of "cookietheme"
    if (!array_key_exists("cookietheme", $_COOKIE)) {

        $new_visitor = true;
        $new_theme = 'default';
        echo "Setting new cookie for theme";
        setcookie('cookietheme', $new_theme, time() + 5);

        setcookie('visits', 0);

    } else {
        $new_visitor = false;
        echo "Already visited";
        $theme = $_COOKIE['cookietheme'];
        echo "CookieTheme: $theme";
        $visits = intval($_COOKIE['visits']) + 1;
        setcookie('visits', $visits);
    }
}
// If the theme is set by post, change the theme
// And set a cookie
/*if(isset($_POST['myTheme'])) {
   $theme = $_POST['myTheme']; 
}*/

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
<?php
    if(isset($theme) ) {
        if($theme == 'Winter') {
            echo "<h1 class='winter'>";
        } else if($theme == 'Summer') {
            echo "<h1 class='summer'>";
        } else if($theme == 'Spring') {
            echo "<h1 class='spring'>";
        } else if($theme == 'Fall') {
            echo "<h1 class='fall'>";
        } else if($theme == 'default') {
            echo "<h1 class='default'>";
        }

    }else {
        echo "<h1>";
    }
    echo "$month_str $year</h1>";

?>

<div id="ROW">
<table>
<tr>
<td>
<?php
    $prev_month = $month-1;
    $prev_year = $year;
    if($prev_month == 0) {
        $prev_month = 12;
        $prev_year = $year-1;
    }
    echo "<a href='http://www.cis.gvsu.edu/~stevecal/PHP/day5/calendar.php?month=$prev_month&day=$day&year=$prev_year'><<</a>"
?>
</td>

<td>
<div id="TABLE">
<table>
    <?php
        if(isset($theme)) {
            if($theme == 'Winter') {
                echo "<tr class='winter'>";
            }else if($theme == 'Summer') {
                echo "<tr class='summer'>";
            }else if($theme == 'Spring') {
                echo "<tr class='spring'>";
            }else if($theme == 'Fall') {
                echo "<tr class='fall'>";
            }else if($theme == 'Default') {
                echo "<tr class='default'";
            }
        } else {
            echo "<tr class='default'>";
        }
        echo "<th>Sunday</th><th>Monday</th>";
        echo "<th>Tuesday</th><th>Wednesday</th><th>Thursday</th>";
        echo "<th>Friday</th><th>Saturday</th> </tr>";

    ?>
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
                if(isset($theme)) {
                    if($theme == 'Winter') {
                        echo "<td class='winter'>$day_x</td>";
                    }else if($theme == 'Summer') {
                        echo "<td class='summer'>$day_x</td>";
                    }else if($theme == 'Spring') {
                        echo "<td class='spring'>$day_x</td>";
                    }else if($theme == 'Fall') {
                        echo "<td class='fall'>$day_x</td>";
                    }else if($theme == 'default') {
                        echo "<td class='default'>$day_x</td>";
                    }
                } else {
                    echo "<td>$day_x</td>";
                }
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
</div>
</td>

<td>
<?php
    $next_month = $month+1;
    $next_year = $year;
    if($next_month == 13) {
        $next_month = 1;
        $next_year = $year + 1;
    }
    echo "<a href='http://www.cis.gvsu.edu/~stevecal/PHP/day5/calendar.php?month=$next_month&day=$day&year=$next_year'>>></a>"
?>
</td>
</tr>
</table>
</div>

<hr>

<fieldset>
<legend>Data</legend>
<form action="calendar.php" method="post">
    Select a Theme: <br>
    Winter <input type="radio" name="myTheme" value="Winter"/><br>
    Spring <input type="radio" name="myTheme" value="Spring"/><br>
    Summer <input type="radio" name="myTheme" value="Summer"/><br>
    Fall   <input type="radio" name="myTheme" value="Fall"/><br>

    <input type="submit" name="submit" value="Submit"/>
</form>
</fieldset>

</body>
</html>

<?php
    // if selected a theme, set a new cookie
    if(isset($_POST['myTheme'])) {
        setcookie('cookietheme', $_POST['myTheme'], time() + 5);
    }
?>
