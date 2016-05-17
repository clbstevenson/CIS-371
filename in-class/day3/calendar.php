
<?php
date_default_timezone_set('America/New_York');
$month = date('M');
$year = date('Y');
$day = date('j');
$days = date('t');
$weeks = ceil($days / 7);
$startMonth = time() - (($day-1) * 24 * 60 * 60);
$startOfMonth = date('w', $startMonth);
echo "Today is $day; $month starts on $startOfMonth;\n";
echo "$days days and  $weeks weeks";
switch($startOfMonth) {
    case 0:
    break;
    case 1:
    break;
    case 2:
    break;
    case 3:
    break; 
    case 4: 
    break; 
    case 5: 
    break; 
    case 6: 
    break;
?>

<html>

<head>
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
        for ($weeknum = 0; $weeknum < $weeks; $weeknum++) {
            echo "<tr>";
            for ($x = 0; $x < 7; $x++) {
                echo "<td>";
                echo "$x-$weeknum";
                if ($startOfMonth == $x && $weeknum == 0) {
                    echo date('j', $startMonth);
                    //echo date('j', $startOfMonth);
                } else {
                    echo date('j', $startMonth + $x);
                    //echo "other";
                }
                //echo date('w:m-01-Y');
                echo "</td>";    
            }
            echo "</tr>";
        }
    ?>
</table>

</body>
</html>
