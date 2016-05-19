
<!-- This file demonstrates
    (1) How to set up a simple HTML form
    (2) How to use PHP to access the data
    It will be used to collect information about a new friend,
    including their First Name, Last Name, Phone Number, and Age.
-->
<?php error_reporting(E_ALL); ?>
<html>
<head>
    <title>Friend Form</title>
    <style type="text/css">
        #post {
            vertical-align: top;
        }

    </style>
</head>
<body>

<h1>Friend Form</h1>

<h3>Please enter your information below.</h3>



<table>
    <tr>
        <td id="post">

            <fieldset>
                <legend>Information</legend>
                <form action="friendform.php" method="post">
                    First Name: 
                    <input type="text" name="firstname" value=""/><br/>
                    Last Name: 
                    <input type="text" name="lastname" value=""/><br/>
                    Phone Number: 
                    <input type="text" name="phonenumber" value="123-456-7890"/><br/>
                    Age:  
                    <input type="number" name="age" value="18"/><br/>

                    <input type="submit" name="postSubmit" value="Submit"/>
                </form>
            </fieldset>

            <table>
                <tr>
                    <th colspan=2>Contents of <code>$_POST</code></th>
                </tr>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
                <?php
                $datafile = fopen("frienddata.txt", "a") or die("Unable to open file");
                foreach ($_POST as $key => $value) {
                    $printMe = $value;
                    if (is_array($value)) {
                        $printMe = "[" . implode($value, ", ") . "]";
                    }
                    echo "<tr><td>$key</td><td>$printMe</td></tr>\n";
                }
                if (isset($_POST["lastname"])) {
                    fwrite($datafile, $_POST["firstname"] . ",");
                    fwrite($datafile, $_POST["lastname"] . ",");
                    fwrite($datafile, $_POST["phonenumber"] . ",");
                    fwrite($datafile, $_POST["age"] . "\n");
                }
                fclose($datafile);
                ?>
            </table>


        </td>
</table>

<?php

$fp = fopen("php://input", 'r+');
echo stream_get_contents($fp);
?>

<hr>


</body>
</html>
