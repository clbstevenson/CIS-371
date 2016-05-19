<?php

/* Respond to the form submission
    1. Connect to the database and update
    2. Redirect back to friendform
*/

require ('friendDB.php');

if (array_key_exists('ilike', $_POST)) {
    $c = connect();
    add_friend($c, $_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['age']);
}

# redirect back to the original page;
header("Location: friendform.php");
