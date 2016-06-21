
<!-- This file demonstrates
    1.) Displaying the current story id data -> $_GET for story_id
    2.) Display the current event data -> $_POST for event_id
        so users can't see other options
    3.) Accept votes for what option to choose
-->
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
        <?php error_reporting(E_ALL); 
        include "storyDB.php";
        $c = connect_DB();
        session_start();
        //TODO: redirect the user if they are not signed in? or something else
        /*if (! isset($_SESSION["username"])) {
            header("Location: index.php");
        }
        $username = $_SESSION["username"];
        */
        if(isset($_GET['id'])) {
            // if the query string is set, use the specified id
            $story_id = $_GET['id'];
            // but, before that, we also need to check if there is a story
            // with that ID in the database...
            $story_result = get_story_by_id($c, $story_id);
            if (!$story_result) {
                echo "<h3>Sorry, that story doesn't exist yet.</h3>";
                echo "<p>You can also start a new story!</p>";
                echo "<hr>";
                echo "<p><a href='viewStories.php'>Home</a></p>";
                echo "<p>Create Account / Sign In / Logout</p>";
                echo "<!--<h4><a href='index.php'>Home<a></h4>";
                echo "<a href='logout.php'>Logout</a>-->";
                echo "<title>Story Unavailable</title>";
                exit;
            } else {
                /*
                echo "<h3>Found the story with id of $story_id</h3>";
                $story_title = get_story_data_value($c, $story_id, 'title');
                $story_row = get_story_data($c, $story_id);
                echo "<h2>$story_title</h2>";
                $short = $story_row['short_desc'];
                echo "<p>$short</p>";
                echo "<p>" . $story_row['long_desc'] . "</p>";
                */
            }
            
        } else {
            // "Using default story_id";
            $story_id = 9; // by default, use the starting Ye Dungeon story (id=9)
        }
        $story_row = get_story_data($c, $story_id);
        $story_id = $story_row['story_id'];
        $title = $story_row['title'];
        $short_desc = $story_row['short_desc'];
        $long_desc = $story_row['long_desc'];
        //echo $story_row['curr_id'] ."</br>";
        $curr_event_row = get_event_data($c, $story_row['curr_id']);
        $curr_event_id = $curr_event_row['event_id'];
        $curr_choices = get_event_choices($c, $story_row['curr_id']);
        //echo "a: " . $curr_event_row['choice_a'] . $curr_choices[0]."</br>";
        //echo "b: " . $curr_event_row['choice_b'] . $curr_choices[1]."</br>";
        ?>
    <title>Story - <?php echo $title ?></title>
    <link rel="stylesheet" href="css/story.css" type="text/css"/>
    <!-- Include the jQuery library -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <!-- Include the jQuery Countdown CSS and javascript files
            to be used for the timer to end voting -->
    <link rel="stylesheet" type="text/css" href="css/jquery.countdown.css">
    <script type="text/javascript" src="js/jquery.plugin.js"></script> 
    <script type="text/javascript" src="js/jquery.countdown.js"></script>
    <style type="text/css">
        #defaultCountdown { width: 200px; height: 45px; }
        #jstimer {
            display: none;
        }
    </style>
</head>

<?php 
    echo "<p id='data_story_id' class='data_story_id' class='data'>$story_id</p>";
    echo "<p id='data_event_id' class='data_event_id' class='data'>$curr_event_id</p>";
?>

<script>
    var showing_history = false;
    function loadHistory(id) {
        if(!showing_history) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("history").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET", "getHistory.php?id=" + id, true); 
            xmlhttp.send();
            document.getElementById("history_btn").innerHTML = "Hide Full Story";
            document.getElementById("history").style.display = "block";
            showing_history = true;
        } else {
            document.getElementById("history_btn").innerHTML = "Show Full Story";
            document.getElementById("history").innerHTML = "";
            document.getElementById("history").style.display = "none";
            showing_history = false;
        }
    }
</script>
<script type="text/javascript" src="js/timer.js"></script>

<body>

<h1>Story -  
<?php echo $title; ?></h1>

<div id="story_info">
<p>
<?php echo $story_row['long_desc']  ?>
</p>
<?php
$id = $story_row['story_id'];
echo " <button id='history_btn' type='button' onclick='loadHistory($id)'>Show Full Story</button>"
?>
<div id="history">
</div>
<hr>
<p>
<?php echo $curr_event_row['description']  ?>
</p>
<p>
<?php echo $curr_event_row['result']  ?>
</p>
</div>

<fieldset>
<legend>Choices</legend>
<div id="div_choices">
<form id="choices" action="" method="post">
<input type="radio" name="option"
<?php
    $curr_event_a = $curr_event_row['choice_a'];
    echo "<input type='radio' id='optionA' name='option' checked='true' value='$curr_event_a'>$curr_choices[0]</br>";
    $curr_event_b = $curr_event_row['choice_b'];
    echo "<input type='radio' id='optionB' name='option' value='$curr_event_b'>$curr_choices[1]</br>";
?>
<input type="submit" name="postSubmit" value="Submit"/>
</form>
</div>
<span>
<p>Time until voting ends: </p>
<!--<p id="timer">-1</p>-->
<p id="jstimer"></p>
<div id="defaultCountdown"></div>
<div id="noDays"></div>
</span>
</fieldset>

<hr>

<p id="demo"></p>


<p><a href='viewStories.php'>Home</a></p>
<p>Logout</p>
<!--<h4><a href="index.php">Home<a></h4>
<a href="logout.php">Logout</a>-->

<script type="text/javascript">

    // Javscript functions 
    function submit_vote(eventID) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("div_choices").innerHTML = xmlhttp.responseText;
            }
        };
        var story_id = document.getElementById("data_story_id").innerHTML;
        var event_id = document.getElementById("data_event_id").innerHTML;
        var option = document.querySelector('input[name = "option"]:checked').value;
        console.log("You selected " + option + "!<br>");
        console.log("story_id: " + story_id + "; event_id: " + event_id + ";choice_id: " + option);
        xmlhttp.open("GET", "getVotes.php?story_id=" + story_id + "&event_id="+event_id + "&choice_id=" + option, true); 
        xmlhttp.send();
        //document.getElementById("history_btn").innerHTML = "Hide History";
        //document.getElementById("history").style.display = "block";
    }

    function countdown() {
        //document.getElementById("countdown").innerHTML = 
    }

    // Non-Functions javascript
    document.getElementById("choices").addEventListener("submit",
        function(event){
            event.preventDefault();
            document.getElementById("demo").innerHTML += "choose ";
            submit_vote(event);
        });
</script>
<?php
$c->close();
?>

</body>
</html>
