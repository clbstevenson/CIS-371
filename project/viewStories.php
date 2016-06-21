
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
    <title>Story List</title>
    <!--<style type="text/css" src="story.css"></style>-->
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
<body>
<script type="text/javascript" src="timer.js"></script>

<h1>Available Stories </h1>

<?php 
include "storyDB.php";
$c = connect_DB();
// The second parameter is $with_links
// that, when true, will display the title column with a link to story page 
display_stories_basic($c, 1);
?>


<p id="demo"></p>

<!--<script src="sortProfs.js"></script>-->
<script>
    var table_html = document.getElementById("view_stories");
    console.log(table_html);
    var table_tbody = table_html.firstChild;

    // Note: I know there is a better way to add same action to multiple
    // objects where they don't all change/mess with the params of the
    // other listeners, yet this is sufficient for now.
    // TODO: use a loop to add the listeners (in case more cols are added).
    var short_descs = table_html.getElementsByClassName("short_desc");
    console.log(short_descs);
    var h0 = short_descs[0].innerHTML;
    /*
    short_descs[0].addEventListener("click", function(){
            document.getElementById("demo").innerHTML = "story time?";
            
            // Collect the story_id from the parent node (a tr element)
            var clicked_row = short_descs[0].parentNode;
            var row_id = clicked_row.id;
            console.log("row_id: " + row_id);
            // TODO: Use AJAX to query server for more info, then display
            // Query the database for the long_description of story row_id
            //var long_desc = <?php 
            //    get_story_data_value($c, $row_id, 'long_desc');
            //    ?>;

            console.log("long_desc: " + long_desc);
            var new_col = document.createElement("TD");
            var new_col_text = <?php ?>0 ;
            
            //var row_parent = rows[1].parentNode;
            // as long as the parent of the rows has more rows, remove them
            //while (table_tbody.hasChildNodes()) {
            //    table_tbody.removeChild(table_tbody.firstChild);
            //}
            //testing("LISTTABLE", h0, 0);
    });
    */
    /*
    var h1 = headers[1].innerHTML;
    headers[1].addEventListener("click", function(){
            testing("LISTTABLE", h1, 1);
    });
    var h2 = headers[2].innerHTML;
    headers[2].addEventListener("click", function(){
            testing("LISTTABLE", h2, 2);
    });
    var h3 = headers[3].innerHTML;
    headers[3].addEventListener("click", function(){
            testing("LISTTABLE", h3, 3);
    });
    */

    /*for ( i = 0; i < headers.length; i++) {
        console.log("headers[" + i + "]: " + headers[i]);
        var hh = headers[i].innerHTML;
        console.log("headers[" + i + "] html: " + headers[i].innerHTML);
        headers[i].addEventListener("click", function(){
            testing("LISTTABLE", hh, i);
        });
    }
    */
</script>

<hr>
<p id="jstimer"></p>
<p>Home</p>
<p>Create Account / Sign In / Logout</p>
<!--<h4><a href='index.php'>Home<a></h4>
<a href='logout.php'>Logout</a>-->

<?php
$c->close();
?>

</body>
</html>
