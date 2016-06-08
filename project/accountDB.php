<?php

function create_accounts_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS friendAccounts( ".
        "account_id INT NOT NULL PRIMARY KEY,".
        "name VARCHAR(50) NOT NULL,".
        "password VARCHAR(50) NOT NULL,".
        "superuser BOOLEAN);";
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the friendAccounts table [" . $c->error . "]");
    }
    $sql = "INSERT IGNORE INTO  friendAccounts (name, password, superuser) VALUES ('testaccount','abcd123','1');";
    //$return_val = $c->query($sql);
    //if(!$return_val) {
    //    die("Could not insert into friendAccounts table [" . $c->error . "]");
    //}


    //read_from_file($c, "frienddata.txt");

    return $return_val;
            
}

function connect_accounts_DB() {
    //echo "Connecting...";
    $connection = new mysqli("127.0.0.1", "stevecal", "stevecal9889");

    // Complain if the connection fails. 
    if (!$connection || $connection->connect_error) {
        die('Unable to connect to database [' . $conection->connect_error . ']');
    }
    if (!$connection->select_db("stevecal")) {
        die ("Unable to seelct database: [" . $connection->error . "]");
    }

    //echo "Connected!" . "<br>";
    return $connection;
}

function add_account($c, $p_name, $p_pass, $p_superuser) {
    //echo "Adding a friend..." . "<br>";
    if(!$p_name) {
        $msgf = "Sorry, you need to include a user name.<br>";
        echo "add_account error: " . $msgf;
        throw new Exception($msgf);
        //return $p_fname;
    }
    if(!$p_pass) {
        $msgl = "Sorry, you need to include a password.<br>";
        echo "add_account error: " . $msgl;
        throw new Exception($msgl);
        //return $p_lname;
    }
    $set_superuser = $p_superuser;
    if(!$p_superuser) {
        $set_superuser = 0; // if not defined, then set as standard user
    }
    $sql = "INSERT IGNORE INTO friendAccounts (name, password, superuser) VALUES ('$p_name', '$p_pass', '$set_superuser');";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into friendAccounts table [" . $c->error . "]");
    }
    //echo "Added a new friend!" . "<br>";
    return $return_val;
}

function add_my_friend($c, $username, $p_fname, $p_lname, $p_phone, $p_age) {
    if(!has_permission($c, $username)) {
        echo "Sorry, you do not have permission to add friends.";
        return false;
    }
    //echo "Adding a friend..." . "<br>";
    if(!$p_fname) {
        $msgf = "Sorry, that friend is missing a first name. Please try again<br>";
        echo "add_friend error: " . $msgf;
        throw new Exception($msgf);
        //return $p_fname;
    }
    if(!$p_lname) {
        $msgl = "Sorry, that friend is missing a last name. Please try again<br>";
        echo "add_friend error: " . $msgl;
        throw new Exception($msgl);
        //return $p_lname;
    }
    if(!$p_phone) {
        $msgp = "Sorry, that friend is missing a phone number. Please try again<br>";
        echo "add_friend error: " . $msgp;
        throw new Exception($msgp);
        //return $p_phone;
    }
    if(!$p_age) {
        $msga = "Sorry, that friend is missing an age. Please try again<br>";
        echo "add_friend error: " . $msga;
        throw new Exception($msga);
        //return $p_age;
    }
    $sql = "INSERT IGNORE INTO  friends (fname, lname, phone, age, account_id) VALUES ('$p_fname', '$p_lname', '$p_phone', '$p_age', (SELECT account_id FROM friendAccounts WHERE name = '$username'));";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into friends table [" . $c->error . "]");
    }
    //echo "Added a new friend!" . "<br>";
    return $return_val;
}

function get_all_accounts($c) {
    //echo "Selecting all friends...";
    $sql = "select * from friendAccounts";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    //echo "Found all friends!" . "<br>";
    return $result;
}

function get_my_friends($c, $username) {
    $sql = "SELECT * FROM friends WHERE account_id = (SELECT account_id FROM friendAccounts WHERE name = '$username');";
    //echo "QUERY:\t$sql<br/>";
    if(!$result = $c->query($sql)) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;

}

// This function may not be necessary for friendAccounts,
// but it is left here in case it should be updated to
// add accounts from a text file.
function read_from_file_accounts($c, $filename, $username) {

    if(!has_permission($c, $username)) {
        echo "Sorry, you do not have permission to add friends.";
        return false;
    }
    
    //echo "Reading friend data from a file" . "<br>";
    
    $datafile = fopen($filename, "r") or die("Unable to open file for reading");
    //echo fread($datafile, filesize($filename));
    // output one line until end-of-file
    while(!feof($datafile)) {
        $line = fgets($datafile);
        //echo "<h4>debug: line=$line</h4>";
        
        // get first name from the current line
        $token = strtok($line, ",");
        $p_fname = $token;
        // get last name from the current line
        $token = strtok(",");
        $p_lname = $token;
        // get phone number from the current line
        $token = strtok(",");
        $p_phone = $token;
        $new_p_phone = str_replace("-", "", $p_phone);
        // get age from the current line
        $token = strtok(",");
        $p_age = $token;
        //echo "Read: $p_fname::$p_lname::$p_phone/$new_p_phone::$p_age<br>";
        if($p_fname) {
            add_my_friend($c, $username, $p_fname, $p_lname, $new_p_phone, $p_age);
        }
    }
    
    fclose($datafile);

    //echo "Done reading friend data!" . "<br>";
}
function display_accounts($c) {
    //echo "Displaying friends...";
    $result = get_all_accounts($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    echo "<table>";
    echo "<tr><th>User Name</th><th>Superuser</th>";
    foreach ($result as $row) {
        echo "<tr>";
        $keys = array("name", "superuser");
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
    //echo "Done displaying friends!<br>";
}
function display_my_friends($c, $name) {
    //echo "Displaying friends...";
    $result = get_my_friends($c, $name); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    //echo "Done displaying friends!<br>";
    echo "<table>";
    echo "<tr><th>First Name</th><th>Last Name</th><th>Phone Number</th><th>Age</th>";
    foreach ($result as $row) {
        echo "<tr>";
        $keys = array("fname", "lname", "phone", "age");
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
}

function has_permission($c, $username) {
    $sql = "SELECT superuser FROM friendAccounts WHERE name = '$username';";
    if(!$result = $c->query($sql)) {
        die("Unable to process permissions query [".$c->error."]");
    }
    //echo "permissions result = $result";
    echo "<br/>";
    while($row = $result->fetch_assoc()) {
        $permission = $row['superuser'];
        echo $row['superuser'] . '<br/>';
    }
    return $permission;
}
//$c = connect_DB();
//$create_result = create_DB($c);
//$result = get_all($c);
//$c->close();


$c = connect_accounts_DB();
create_accounts_DB($c);
//display_friends($c);

//read_from_file($c, "frienddata.txt");
//display_friends($c);

?>
