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
    if(!$return_val) {
        die("Could not create the friends table [" . $c->error . "]");
    }
    $sql = "INSERT IGNORE INTO  friends (fname, lname, phone, age) VALUES ('Timmy','Barker','1234567890',14);";
    //$return_val = $c->query($sql);
    //if(!$return_val) {
    //    die("Could not insert into friends table [" . $c->error . "]");
    //}

    return $return_val;
            
}

function connect_DB() {
    $connection = new mysqli("127.0.0.1", "stevecal", "stevecal9889");

    // Complain if the connection fails. 
    if (!$connection || $connection->connect_error) {
        die('Unable to connect to database [' . $conection->connect_error . ']');
    }
    if (!$connection->select_db("stevecal")) {
        die ("Unable to seelct database: [" . $connection->error . "]");
    }

    return $connection;
}

function add_friend($c, $p_fname, $p_lname, $p_phone, $p_age) {
    $sql = "INSERT IGNORE INTO  friends (fname, lname, phone, age) VALUES ($p_fname, $p_lname, $p_phone, $p_age);";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into friends table [" . $c->error . "]");
    }
    return $return_val;
}

function get_all($c) {
    $sql = "select * from friends";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

function read_from_file($c, $filename) {
    $datafile = fopen($filename, "r") or die("Unable to open file for reading");
    echo fread($datafile, filesize($filename));
    fclose($datafile);
}
function display_friends($c) {
   $result = get_all($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    echo "<table>";
    foreach ($result as $row) {
        echo "<tr>";
        $keys = array("fname", "lname", "phone", "age");
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>";
}
//$c = connect_DB();
//$create_result = create_DB($c);
//$result = get_all($c);
//$c->close();
?>
