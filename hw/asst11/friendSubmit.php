<?php

/* Respond to the form submission
    1. Connect to the database and update
    2. Redirect back to friendform
*/

require ('friendDB.php');

if (array_key_exists('firstname', $_POST)) {
    $c = connect_DB();
    add_friend($c, $_POST['firstname'], $_POST['lastname'], $_POST['phonenumber'], $_POST['age']);
}

# redirect back to the original page;
header("Location: friendform.php");
