<?php

/* Respond to the form submission
    1. Connect to the database and update
    2. Redirect back to friendform
*/
require ('accountDB.php');
session_start();
if(isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}else {
    header("Location: index.php");
}

if (array_key_exists('firstname', $_POST)) {
    $c = connect_accounts_DB();
    if(!add_my_friend($c, $username, $_POST['firstname'], $_POST['lastname'], $_POST['phonenumber'], $_POST['age'])) {
        header("Location: friendAdded.php");    
    } else {
        header("Location: friendAdded.php");
    }

} else {
    # redirect back to the original page;
    header("Location: friendAdded.php");
}
