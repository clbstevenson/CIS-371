<?php

$debug = 1;
//
// Connect to the database so it can be queried and accessed.
//
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

//
//
// Create the table for the accounts users can use to sign in to access
// various information.
function create_accounts_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS story_accounts( ".
        "account_id INT NOT NULL PRIMARY KEY,".
        "name VARCHAR(50) NOT NULL,".
        "password VARCHAR(50) NOT NULL,".
        "superuser BOOLEAN);";
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the story_accounts table [" . $c->error . "]");
    }
    //$sql = "INSERT IGNORE INTO  friendAccounts (name, password, superuser) VALUES ('testaccount','abcd123','1');";
    //$return_val = $c->query($sql);
    //if(!$return_val) {
    //    die("Could not insert into friendAccounts table [" . $c->error . "]");
    //}
    return $return_val;
            
}

//
// Create the table for storying the general base Story elements
// including a title, a short description, and the longer plot description.
function create_story_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS story( ".
        "story_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,".
        "title VARCHAR (50),".
        "short_desc VARCHAR (50),".
        "long_desc VARCHAR (512),".
        "start_id INT,".
        "FOREIGN KEY (start_id) REFERENCES event(event_id));";
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the story table [" . $c->error . "]");
    }
    //$sql = "INSERT IGNORE INTO  friendAccounts (name, password, superuser) VALUES ('testaccount','abcd123','1');";
    //$return_val = $c->query($sql);
    //if(!$return_val) {
    //    die("Could not insert into friendAccounts table [" . $c->error . "]");
    //}


    //read_from_file($c, "frienddata.txt");

    return $return_val;
            
}

//
// Create the table for the various Events aspects for each story.
function create_event_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS event( ".
		"event_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,".
		"description VARCHAR (128),".
		"result VARCHAR (256),".
		"choice_a INT NOT NULL,".
		"choice_b INT NOT NULL,".
		"FOREIGN KEY (choice_a) REFERENCES event(event_id),".
		"FOREIGN KEY (choice_b) REFERENCES event(event_id)); "
	;
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the event table [" . $c->error . "]");
    }
    return $return_val;
}

//
// Creates the table for connecting the Story and Event tables
// so each story_id has a 'list' of events tied to it.
function create_story_event_DB($c) {
    $sql = "CREATE TABLE IF NOT EXISTS story_event ( ".
		"story_id INT NOT NULL,".
		"event_id INT NOT NULL,".
		"FOREIGN KEY (story_id) REFERENCES story(story_id),".
		"FOREIGN KEY (event_id) REFERENCES event(event_id),".
		"PRIMARY KEY (story_id, event_id));"
	;
    $return_val = $c->query($sql);
    //echo "Created table";
    if(!$return_val) {
        die("Could not create the story_event table [" . $c->error . "]");
    }
    return $return_val;
}

// Functions for making additions to the connected database tables.

// TODO: Add a new user account 
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
    $sql = "INSERT IGNORE INTO story_accounts (name, password, superuser) VALUES ('$p_name', '$p_pass', '$set_superuser');";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into story_accounts table [" . $c->error . "]");
    }
    //echo "Added a new friend!" . "<br>";
    return $return_val;
}

// Add the basis for a new Story to the database
function add_story($c, $title, $short_desc, $long_desc) {
    $msg = "Sorry, try again. The story is missing a ";
    $error = false;
    if(!$title) {
        $msg += "title.";
        $error = true;
    }
    if(!$short_desc) {
        $msg += "short description.";
        $error = true;
    }
    if(!$long_desc) {
        $msg += "long description.";
        $error = true;
    }
    // If there is an error, throw it with the appropriate message.
    if($error) {
        throw new Exception($msg);
    }

    $sql = "INSERT IGNORE INTO  story (title, short_desc, long_desc)".
        " VALUES ('$title', '$short_desc', '$long_desc');";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into story table [" . $c->error . "]");
    }
    return $return_val;
}

// Add a new Event or format for an Event to the database
function add_event($c, $desc, $result, $choice_a, $choice_b) {
    $msg = "Sorry, try again. The event is missing a ";
    $error = false;
    if(!$desc) {
        $msg += "description.";
        $error = true;
    }
    if(!$result) {
        $msg += "description for the result.";
        $error = true;
    }
    // choice_a and choice_b CAN be null
    // If there is an error, throw it with the appropriate message.
    if($error) {
        throw new Exception($msg);
    }

    $sql = "INSERT IGNORE INTO  event (description, result, choice_a, choice_b)".
        " VALUES ('$desc', '$result', '$choice_a', '$choice_b');";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into event table [" . $c->error . "]");
    }
    return $return_val;
}

// Add a new story_event element. This is pairing one story with one event. 
// This function selects the Story based on the story title,
// and chooses the Event based on the description.
// To avoid duplicate/multiple story/event selections, see the method
//      add_story_event_by_id($c, $story_id, $event_id).
function add_story_event_by_title($c, $story_title, $event_desc) {
    $msg = "Sorry, try again. The story_event is missing a ";
    $error = false;
    if(!$story_title) {
        $msg += "title.";
        $error = true;
    }
    if(!$event_desc) {
        $msg += "short event description.";
        $error = true;
    }
    // If there is an error, throw it with the appropriate message.
    if($error) {
        throw new Exception($msg);
    }

    $sql = "INSERT IGNORE INTO  story_event (story_id, event_id) ".
       "VALUES (".
       "(SELECT story_id FROM story WHERE title = '$story_title'),".
       "(SELECT event_id FROM event WHERE description = '$event_desc'));";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into story_event table by title[" . $c->error . "]");
    }
    return $return_val;
}

// Add a new story_event element. This is pairing one story with one event. 
//      add_story_event_by_id($c, $story_id, $event_id).
function add_story_event_by_id($c, $story_id, $event_id) {
    $msg = "Sorry, try again. The story_event is missing a ";
    $error = false;
    if(!$story_id) {
        $msg += "story id.";
        $error = true;
    }
    if(!$event_id) {
        $msg += "event_id.";
        $error = true;
    }
    // If there is an error, throw it with the appropriate message.
    if($error) {
        throw new Exception($msg);
    }

    // Note: Really, probablly don't need the nested queries..
    $sql = "INSERT IGNORE INTO  story_event (story_id, event_id) ".
       "VALUES (".
       "(SELECT story_id FROM story WHERE story_id = '$story_id'),".
       "(SELECT event_id FROM event WHERE event_id = '$event_id'));";
    $return_val = $c->query($sql);
    if(!$return_val) {
        die("Could not insert into story_event table by id [" . $c->error . "]");
    }
    return $return_val;
}

// TODO: update to work for project accounts
function get_all_accounts($c) {
    //echo "Selecting all user accounts...";
    $sql = "select * from story_accounts";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

// Select all of the created Stories.
function get_all_stories($c) {
    //echo "Selecting all stories...";
    $sql = "select * from story";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

// Select all of the created Events.
function get_all_events($c) {
    //echo "Selecting all events...";
    $sql = "select * from event";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

function get_start_event($c, $start_id) {
    //echo "Selecting all events...";
    $sql = "select * from event WHERE event_id = $start_id";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

function get_story_by_id($c, $story_id){
    $sql = "select * from story where story_id = $story_id";
    $result = $c->query($sql);
    // return the result. If null, then result is null as checked
    // by the calling function, which should display appropriate message.
    if(mysqli_num_rows($result) < 1) {
        $result = false;
        return false;
    } else {
        //echo "result is NOT null";
    }
    //echo "</br>";
    return $result;
}
function get_event_by_id($c, $event_id){
    $sql = "select * from event WHERE event_id = $event_id";
    $result = $c->query($sql);
    // return the result. If null, then result is null as checked
    // by the calling function, which should display appropriate message.
    if(mysqli_num_rows($result) < 1) {
        $result = false;
        return false;
    }
    return $result;
}

// Select all of the created story_event pairs.
function get_all_story_events($c) {
    //echo "Selecting all story_events...";
    $sql = "select * from story_event";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

//TODO: add similar functions for reading new story/event info from files.
// This function may not be necessary for friendAccounts,
// but it is left here in case it should be updated to
// add accounts from a text file.

/*function read_from_file_accounts($c, $filename, $username) {

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
* end read_from_file_accounts() 
*/

// Collect all of the accounts info, then return a html table with the data.
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

// Collect all of the stories, then return a html table with the data.
// TODO: mainly used for testing, so update this to display more nicely.
function display_stories($c) {
    //echo "Displaying friends...";
    $result = get_all_stories($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    echo "<h3>Stories</h3>";
    echo "<table>";
    echo "<tr><th>Story Title</th><th>Short Description</th><th>Long Description</th><th>Start Event</th></tr>";
    foreach ($result as $row) {
        echo "<tr>";
        $keys = array("title", "short_desc", "long_desc", );
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        // query and retrieve the 'start event' for the current story row
        echo "<td>";
        //echo "start_id: " . $row['start_id'];
        //$event_result = "";
        $event_result = get_start_event($c, $row['start_id']); 
        echo "<table class='start_event_table'";
        echo "<tr><th>ID</th><th>Description</th><th>Result</th></tr>";
        foreach ($event_result as $event_row) {
            echo "<tr>";
            $event_keys = array("event_id", "description", "result");
            // iterate over all the selected event's columns
            foreach ($event_keys as $event_key) {
                echo "<td>" . $event_row[$event_key] . "</td>";
            }
            echo "</tr>\n";
        }
        echo "</table>";
        echo "</td>";


        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
}

// Collect all of the events, then return a html table with the data.
// TODO: mainly used for testing, so update this to display more nicely.
function display_events($c, $with_id) {
    //echo "Displaying friends...";
    $result = get_all_events($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    echo "<h3>Events</h3>";
    echo "<table><tr>";
    if($with_id){
        echo "<th>ID</th>";

    }
    echo "<th>Description</th><th>Result Text</th><th>Choice A</th><th>Choice B</th></tr>";
    foreach ($result as $row) {
        echo "<tr>";
        if($with_id) {
            $keys = array("event_id", "description", "result", "choice_a", "choice_b");
        } else {
            $keys = array("description", "result", "choice_a", "choice_b");
        }
    
        // iterate over all the columns.  Each column is a <td> element.  
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
}


// Collect all of the story_events, then return a html table with the data.
// TODO: mainly used for testing, so update this to display more nicely.
function display_story_events($c) {
    //echo "Displaying friends...";
    $result = get_all_story_events($c); 

    // iterate over each record in the result.
    // Each record will be one row in the table, beginning with <tr> 
    echo "<h3>Story Events</h3>";
    echo "<table>";
    echo "<tr><th>Story ID</th><th>Event ID</th></tr>";
    foreach ($result as $row) {
        echo "<tr>";
        $keys = array("story_id", "event_id");
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    echo "</table>" . "<br>";
}

// This function queries the story table for row data with $story_id.
function get_story_data($c, $id) {
    $sql = "SELECT * FROM story WHERE story_id = $id";
    $result = $c->query($sql);
    foreach ($result as $row) {
        return $row;
    }
}

// This function queries the story table for $value with $story_id.
function get_story_data_value($c, $id, $value) {
    $sql = "SELECT $value FROM story WHERE story_id = $id";
    $result = $c->query($sql);
    foreach ($result as $row) {
        return $row[$value];
    }
    /*foreach ($result as $row) {
        echo "<tr>";
        $keys = array("story_id", "event_id");
        // iterate over all the columns.  Each column is a <td> element.
        foreach ($keys as $key) {
            echo "<td>" . $row[$key] . "</td>";
        }
        echo "</tr>\n";
    }
    */
}

// This function queries the event table for row data with $event_id.
function get_event_data($c, $id) {
    $sql = "SELECT * FROM event WHERE event_id = $id";
    $result = $c->query($sql);
    foreach ($result as $row) {
        return $row;
    }
}

//TODO: update this function to work with user accounts not friend accs.
function has_permission($c, $username) {
    $sql = "SELECT superuser FROM story_accounts WHERE name = '$username';";
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

// The following functions test inserting new stories/events to the db.

function test_insert_story($c, $title, $short_desc, $long_desc) {
    $sql = "REPLACE INTO story (title, short_desc, long_desc) ".
        "VALUES ('$title', '$short_desc', '$long_desc');";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;

}
// The following functions test inserting new events to the db.
function test_insert_event($c, $desc, $result, $choice_a, $choice_b) {
    if(!$choice_a)
        $choice_a = "NULL";
    if(!$choice_b)
        $choice_b = "NULL";

    echo "</br>";
    echo "choice_a: " . $choice_a;
    echo "choice_b: " . $choice_b;
    echo "</br>";

    $sql = "REPLACE INTO event (description, result, choice_a, choice_b) ".
        "VALUES ('$desc', '$result', $choice_a, $choice_b);";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;

}

// The following functions test inserting new events to the db.
function test_insert_story_event($c, $story_id, $event_id) {
    $sql = "INSERT INTO story_event (story_id, event_id) ".
        "VALUES ('$story_id', '$event_id');";
    $result = $c->query($sql);
    if (!$result) {
        die ("Query was unsuccessful: [" . $c->error ."]");
    }
    return $result;
}

?>

<html>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!--<title>StoryDB</title>-->
    <style type="text/css">
        #post {
            vertical-align: top;
            }
        table td, table th, table {
            border: 1px solid gray;
            text-align: center;
        }


    </style>
</head>
<body>

<?php
//$c = connect_DB();
//$create_result = create_DB($c);
//$result = get_all($c);
//$c->close();


$c = connect_DB();
if($debug) {
    create_story_DB($c);
    create_event_DB($c);
    create_story_event_DB($c);
    echo "<hr>";
    display_stories($c);
    echo "<hr>";
    // if second parameter is true, then the event_ids will also be shown.
    display_events($c, 1);
    echo "<hr>";
    display_story_events($c);
    //display_friends($c);

    // * SUCCESS *
    // Stories can be successfully inserted into the database.
    // HOWEVER, duplicates are not ignored -> should look into.
    //test_insert_story($c, 'Dungeon 1', 'Ye find yeself in a dark dungeon',
    //    'Ye find yeself in a dark dungeon room with a grimy gray floor. Ye see a flask on the floor next to ye. There is one exit to ye right.');
    echo "<hr>";
    // TODO: figure out how to insert choices for an event.
    echo "PRE-TEST_INSERT_EVENT";
    //test_insert_event($c, 'Pick up the flask', 'Ye pick up ye flask. Inside is a dull orange liquid. There is one exit to ye right.', NULL, NULL);
    //test_insert_event($c, 'Ye enter ye dungeon.', 'Ye find yeself in a dark dungeon room with a grimy gray floor. Ye see a flask on the floor next to ye. There is one exit to ye right.', NULL, NULL);
    //test_insert_event($c, 'Go through the exit.', 'Ye approach the exit. As you get closer ye hear a faint clicking sound further down the tunnel. Perhaps it is some fowl creature? or merely the ambiant noise of a dark tunnel.', NULL, NULL);
    //test_insert_event($c, 'Go through the exit.', 'Ye approach the exit. As you get closer ye hear a faint clicking sound further down the tunnel. Perhaps it\'s some fowl creature? or merely some ambiant noise of a tunnel.', NULL, NULL);
    //test_insert_story_event($c, 9, 30);
    echo "<hr>";
    display_stories($c);
    echo "<hr>";

}

?>
</body>
</html>
