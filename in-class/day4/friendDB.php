<?php

function create_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS friends( ".
        "id INT NOT NULL AUTO_INCREMENT,".
        "fname VARCHAR(100) NOT NULL,".
        "lname VARCHAR(100) NOT NULL,".
        "phone VARCHAR(10) NOT NULL,".
        "age INT NOT NULL,".
        "PRIMARY KEY ( id ));";
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the friends table [" . $c->error . "]");
    }
    $sql = "INSERT IGNORE INTO  friends (fname, lname, phone, age) VALUES ('Timmy','Barker','1234567890',14);";
    //$return_val = $c->query($sql);
    //if(!$return_val) {
    //    die("Could not insert into friends table [" . $c->error . "]");
    //}


    //read_from_file($c, "frienddata.txt");

    return $return_val;
            
}

function connect_DB() {
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

function add_friend($c, $p_fname, $p_lname, $p_phone, $p_age) {
    //echo "Adding a friend..." . "<br>";
    if(!$p_fname) {
        echo "Sorry, that friend is missing a first name. Please try again<br>";
        return $p_fname;
    }
    if(!$p_lname) {
        echo "Sorry, that friend is missing a last name. Please try again<br>";
        return $p_lname;
    }
    if(!$p_phone) {
        echo "Sorry, that friend is missing a phone number. Please try again<br>";
        return $p_phone;
    }
    if(!$p_age) {
        echo "Sorry, that friend is missing an age. Please try again<br>";
        return $p_age;
    }
    $sql = "INSERT IGNORE INTO  friends (fname, lname, phone, age) VALUES ('$p_fname', '$p_lname', '$p_phone', '$p_age');";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into friends table [" . $c->error . "]");
    }
    //echo "Added a new friend!" . "<br>";
    return $return_val;
}

function get_all($c) {
    //echo "Selecting all friends...";
    $sql = "select * from friends";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    //echo "Found all friends!" . "<br>";
    return $result;
}

function read_from_file($c, $filename) {
    //echo "Reading friend data from a file" . "<br>";
    $datafile = fopen($filename, "r") or die("Unable to open file for reading");
    //echo fread($datafile, filesize($filename));
    // output one line until end-of-file
    while(!feof($datafile)) {
        $line = fgets($datafile);
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
        add_friend($c, $p_fname, $p_lname, $new_p_phone, $p_age);
    }
    
    fclose($datafile);

    //echo "Done reading friend data!" . "<br>";
}
function display_friends($c) {
    //echo "Displaying friends...";
    $result = get_all($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
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
    //echo "Done displaying friends!<br>";
}
//$c = connect_DB();
//$create_result = create_DB($c);
//$result = get_all($c);
//$c->close();


$c = connect_DB();
create_DB($c);
//display_friends($c);

//read_from_file($c, "frienddata.txt");
//display_friends($c);

?>
