
<!-- This file demonstrates
    (1) How to set up a simple HTML form
    (2) How to use PHP to access the data
    It will be used to collect information about a new friend,
    including their First Name, Last Name, Phone Number, and Age.
-->
<?php error_reporting(E_ALL); 
//require('friendDB.php');
session_start();
if (! isset($_SESSION["username"])) {
    header("Location: index.php");
}
?>
<html>
<head>
    <title>Friend Form - Add</title>
    <style type="text/css">
        #post {
            vertical-align: top;
        }

    </style>
</head>
<body>

<h1>Friend Form - Add</h1>

<h3>Other pages to update your friends list</h3>
<ul>
<li><a href="friendView.php">View a list of all your friends.</a></li>
<li><a href="friendRead.php">Update friends from a file.</a></li>
</ul>

<h3>Please enter your information below.</h3>

<table>
    <tr>
        <td id="post">
            <fieldset>
                <legend>Information</legend>
                <form action="friendSubmit2.php" method="post">
                    First Name: 
                    <input type="text" name="firstname" value=""/>
                    <br/><br/>
                    Last Name: 
                    <input type="text" name="lastname" value=""/>
                    <br/><br/>
                    Phone Number: 
                    <input type="text" name="phonenumber" value="1234567890"/>
                    <br/><br/>
                    Age:  
                    <input type="number" name="age" value="18"/>
                    <br/><br/>

                    <input type="submit" name="postSubmit" value="Submit"/>


                </form>
            </fieldset>

                <?php
                    $datafile = fopen("frienddata.txt", "a") or die("Unable to open file");
                foreach ($_POST as $key => $value) {
                    $printMe = $value;
                    if (is_array($value)) {
                        $printMe = "[" . implode($value, ", ") . "]";
                    }
                    //echo "<tr><td>$key</td><td>$printMe</td></tr>\n";
                }
                if (isset($_POST["lastname"])) {
                    fwrite($datafile, $_POST["firstname"] . ",");
                    fwrite($datafile, $_POST["lastname"] . ",");
                    fwrite($datafile, $_POST["phonenumber"] . ",");
                    fwrite($datafile, $_POST["age"] . "\n");
                }
                fclose($datafile);
                ?>


        </td>
</table>

<h4><a href="index.php">Home<a></h4>
<a href="logout.php">Logout</a>

<?php

$fp = fopen("php://input", 'r+');
echo "<br>";
echo "<h4>Thank you for your submission!</h4>";
echo "<p>To view your updated friends list, <a href='friendView.php'>visit this page!</a></p>"
?>
</body>
</html>
