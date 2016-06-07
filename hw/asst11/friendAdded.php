
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
$username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Friend Form - Add</title>
    <style type="text/css">
        #post {
            vertical-align: top;
        }
        #notAnInt, #randomInt, #blankFName, #blankLName, #phoneError {
            display: none;
        }
        .redError {
            background-color: red;
        }
        .yellowError {
            background-color: yellow;
        }

    </style>
    <script type="text/javascript" src="underscore.js"></script>
</head>
<body>

<h1>Friend Form - Add</h1>

<h3>Other pages to update your friends list</h3>
<ul>
<li><a href="friendView.php">View a list of all your friends.</a></li>
<li><a href="friendRead.php">Update friends from a file.</a></li>
</ul>

<?php
    include 'accountDB.php';
    $c = connect_accounts_DB();
    if(!has_permission($c, $username)) {
        // user does not have permission to add friends
        echo "<h3> Sorry, you don't have permission to add friends.</h3>";
    } else {
        // Otherwise user is a superuser

?>

<!--- This is an error message that remains hidden unless needed by the validation code -->
<div id="notAnInt" class="myError">
    <div class="redError">Sorry, <span class="entry"></span> is not a number. Try again.
    </div>
</div>
<div id="randomInt" class="myError">
    <div class="redError">Sorry, <span class="entry"></span> is not between 1 and 120. Try again.
    </div>
</div>
<div id="blankFName" class="myError">
    <div class="yellowError">Please enter a first name.</div>
</div>
<div id="blankLName" class="myError">
    <div class="yellowError">Please enter a last name.</div>
</div>
<div id="phoneError" class="myError">
    <div class="redError">Please enter a valid phone number.<br/> It must contain 10 digits, for example (123)4567890</div>
</div>

<h3>Please enter your information below.</h3>

<table>
    <tr>
        <td id="post">
            <fieldset>
                <legend>Information</legend>
                <form id="form_a" action="friendSubmit2.php" method="post">
                    First Name: 
                    <input type="text" name="firstname" id="firstname" value=""/>
                    <br/><br/>
                    Last Name: 
                    <input type="text" name="lastname" id="lastname" value=""/>
                    <br/><br/>
                    Phone Number: 
                    <input type="text" name="phonenumber" id="phone" value="1234567890"/>
                    <br/><br/>
                    Age:  
                    <input type="number" name="age" id="age" value="18"/>
                    <br/><br/>

                    <input type="submit" name="postSubmit" value="Submit"/>


                </form>
            </fieldset>
            <script type="text/javascript">

                function displayError(errorBox, input) {
                    console.log("Display error: ");
                    // Hide all the error boxes (to make sure that only one shows)
                    _.each(document.getElementsByClassName("myError"), function (item) {
                        item.style.display = "none";
                    });

                    // Display the error box
                    errorBox.style.display = "block";

                    console.log("input: " + input.innerHTML);
                    /// Insert the invalid entry in the error message
                    _.each(errorBox.getElementsByClassName("entry"), function (item) {
                        console.log("");
                        item.innerHTML = input;
                    });
                }

                function checkInput(event) {
                    console.log("Checking input...");
                    var entry_fn = document.getElementById("firstname").value.trim();
                    var entry_ln = document.getElementById("lastname").value.trim();
                    var entry_age = document.getElementById("age").value;
                    var entry_ph = document.getElementById("phone").value;

                    var input_age = parseInt(entry_age);
                    if (isNaN(input_age)) {
                        // Prevent the form from submitting.  (In other 
                        // words, don't bother the server with this crap.)
                        event.preventDefault();

                        // Display a (somewhat) appropriate error message.
                        displayError(document.getElementById("notAnInt"), entry_age);
                        return;
                    }
                    if (input_age < 1 || input_age > 120) {
                        // Prevent the form from submitting.  (In other 
                        // words, don't bother the server with this crap.)
                        event.preventDefault();

                        // Display a (somewhat) appropriate error message.
                        displayError(document.getElementById("randomInt"), entry_age);
                        return;
                    }
                    console.log("fname: " + entry_fn);
                    console.log("lname: " + entry_ln);
                    if (!entry_fn) {
                        // Prevent the form from submitting.  (In other 
                        // words, don't bother the server with this crap.)
                        event.preventDefault();

                        // Display a (somewhat) appropriate error message.
                        displayError(document.getElementById("blankFName"), entry_fn);
                        return;
                    }
                    if (!entry_ln) {
                        // Prevent the form from submitting.  (In other 
                        // words, don't bother the server with this crap.)
                        event.preventDefault();

                        // Display a (somewhat) appropriate error message.
                        displayError(document.getElementById("blankLName"), entry_ln);
                        return;
                    }
                    var input_ph = parseInt(entry_ph);
                    if(isNaN(input_ph) || input_ph.length != 10) {
                        // Prevent the form from submitting.  (In other 
                        // words, don't bother the server with this crap.)
                        event.preventDefault();

                        // Display a (somewhat) appropriate error message.
                        displayError(document.getElementById("phoneError"), entry_ph);
                        return;
                    }
                    
                    console.log("Verified Input!");
                }

                document.getElementById("form_a").addEventListener("submit",
                    function(event) {
                        checkInput(event);
                        console.log("anonymous function call");
                    }
                    );
                

                <?php

                ?>
            </script>

                <?php
                /*  This is old code that would write the data from _POST
                    to a text file instead of writing to the database.
                    It is no longer used.
                     
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
                */
                ?>


        </td>
</table>

<?php
    } // end else for if user has permission
?>

<h4><a href="index.php">Home<a></h4>
<a href="logout.php">Logout</a>

<?php
include_once 'accountDB.php';
$fp = fopen("php://input", 'r+');
$c = connect_accounts_DB();
echo "<br>";
if(has_permission($c, $username)) {
    echo "<h4>Thank you for your submission!</h4>";
    echo "<p>To view your updated friends list, <a href='friendView.php'>visit this page!</a></p>";

} else {
    echo "<h4>Sorry, you don't have permission to add friends.</h4>";
}
?>
</body>
</html>
