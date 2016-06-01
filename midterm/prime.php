
<?php

// Caleb Stevenson
// CIS 371 - 2016 - Midterm
// prime.php

date_default_timezone_set('America/New_York');

if(isset($_GET['number'])) {
    // query string is specified, so first $number prime numbers
    $number = $_GET['number'];
    setcookie('cookieNum', $number, time() + 15);
    echo "Using GET number";
} else {
    // if not specified, display first 10 prime numbers
    //$number = 10;

    // if cookie for number of primes doesn't exist, set it
    if (!array_key_exists("cookieNum", $_COOKIE)) {
        $number = 10;
        $new_number = $number;
        echo "Setting new cookieNum: $new_number";
        setcookie('cookieNum', $new_number, time() + 15);
    } else {
        // otherwise, use the cookie value
        $number = $_COOKIE['cookieNum'];
        echo "Using cookieNum: $number";
    }

}

echo "<br>";

if(isset($_GET['columns'])) {
    // query string is specified, so use that many columns 
    $columns = $_GET['columns'];
    echo "Using GET columns";
    setcookie('cookieCol', $columns, time() + 15);
} else {
    // if not specified, display 10 columns 
    //$columns = 10;
    // if cookie for number of primes doesn't exist, set it
    if (!array_key_exists("cookieCol", $_COOKIE)) {
        $columns = 10;
        $new_columns = $columns;
        echo "Setting new cookieCol: $new_columns";
        setcookie('cookieCol', $new_columns, time() + 15);
    } else {
        // otherwise, use the cookie value
        $columns = $_COOKIE['cookieCol'];
        echo "Using cookieCol: $columns";
    }

}
// number of rows is total elements divided by number of columns
// NOTE: currently not used.
$rows = $number / $columns;

function is_prime($n) {
    for ($i = 2; $i * $i <= $n; $i++) {
        if ($n % $i == 0) {
            return false;
        }
    }
    return true;
}

?>

<html>
<head>
    <link rel="stylesheet" href="prime.css" type="text/css"/>
    <title>Prime Numbers</title>
</head>

<body>
<h1> Add The Primes </h1>

<p>Select which primes you want to add</p>

<ul>
<?php
    $id = 0;
    $n = 2;
    while($id < $number) {
        if(is_prime($n)) {
            //echo "<li>$id - $n</li>";    
            $id++;
        }
        $n++;
    }
?>
</ul>

<div id="FormTable">
<fieldset>
<form action="prime.php" method="post">
<table>
    <div id="post">
    <?php
        $items = 0;
        $pnum = 2;
        while ($items < $number) {
            // start a new row
            echo "<tr>";
            // for every column, add another element to the row
            for ($col = 0; $col < $columns; $col++) {
                // while pnum is NOT prime, keep adding 1 until reach
                // the next prime number, then display it.
                while (!is_prime($pnum)) {
                    $pnum++;
                }
                // display the next prime number found
                echo "<td>";
                echo "<input type='checkbox' name='chosen[]' value=$pnum />$pnum " ;
                //echo $pnum;
                echo "</td>";
                $pnum++;
                // another primenumber added, so update $items
                $items++;
                if($items >= $number) {
                    break;
            
                }
            }
            echo "</tr>";
            $pnum++;
                 
        }
        //echo "<input type='submit' name='addThePrimes' value='Submit'/>";
        
    ?>
    <input type='submit' name='addThePrimes' value='Submit'/>
    </div>
    </form>
    </fieldset>
    </div>
</table>



</div>

<div id="Result">
<?php
    
    if (isset($_POST['chosen'])) {
        $chosenPrimes = $_POST['chosen'];

        // set cookies to store the current number of primes and columns
        setcookie('cookieNum', $number, time() + 15);
        setcookie('cookieCol', $columns, time() + 15);
        
        if(empty($chosenPrimes))  {
            echo "<p>No primes selected, so nothing to add.</p>";
        } else {
            $N = count($chosenPrimes);
            $sum = 0;
            echo "<h3>";
            for($i =0; $i+1 < $N; $i++) {
                echo $chosenPrimes[$i] . " + ";
                $sum = $sum + $chosenPrimes[$i];
            }
            echo $chosenPrimes[$i] . " = ";
            $sum = $sum + $chosenPrimes[$i];
            echo $sum;
            echo "</h3>";
        }
    } else {
            echo "<p>No primes selected, so nothing to add.</p>";
    }

?>
</div>
</body>

</html>
