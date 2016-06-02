<?php
include_once 'accountDB.php';
session_start();

if(isset($_POST["username"])) {
    $name = $_POST['username'];
    echo "username is $name";
} else {
    header("Location: login.php");
}
echo "<br/>";
if(isset($_POST["password"])) {
    $pass = $_POST['password'];
    if(!$pass) {
        echo "pass isn't set";
        header("Location: index.php");
    } else {
        echo "password is $pass";

    }

} else {
    header("Location: index.php");
}

$c = connect_accounts_DB();
display_accounts($c);
$sql = "SELECT * FROM friendAccounts WHERE name='$name';";
if(!$result = $c->query($sql)) {
    die("There was an error select from friendAccounts [" .$c->error ."]");
}
echo "<br/>";
if(!$result->num_rows){
    echo "Account not found.<br/>";
    // create a new account
    $result->free();
    //$sql = "INSERT INTO friendAccounts (name, password, superuser) VALUES ('$name', '$pass', '0');";
    //if(!$result = $c->query($sql)) {
    //    die("Error adding account to db [" .$c->error."]");
    //}
    echo "<br/>";
    header("Location: index.php");
    //echo "Account created for $name";
} else {
    echo "Account found!";
    // use the existing account
    // start a new session with the specified user
    while($row = $result->fetch_assoc()) {
        $result_name = $row['name'];
        echo $row['name'] .'<br />';
    }
    $result->free();
    $_SESSION['username']=$name;
    header('Location: index.php');
}
$c->close();

?>

<html>
<body>
<br/>
<a href="index.php">Home</a>
<br/>
</body>
</html>
