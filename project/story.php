
<!-- This file demonstrates
    1.) Displaying the current story id data -> $_GET for story_id
    2.) Display the current event data -> $_POST for event_id
        so users can't see other options
    3.) Accept votes for what option to choose
-->
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
if(isset($_GET['story'])) {
    // if the query string is set, use the specified id
    $story_id = $_GET['story'];
    // but, before that, we also need to check if there is a story
    // with that ID in the database...
    $story_result = get_story_by_id($c, $story_id);
    if (!$story_result) {
        echo "<h3>Sorry, there is not a story with that ID.</h3>";
        exit;
    } else {
        echo "<h3>Found the story with id of $story_id</h3>";
    }
    
} else {
    $story_id = 9; // by default, use the starting Ye Dungeon story (id=9)
    echo "Using default story_id";
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Story</title>
    <style type="text/css">
        #post {
            vertical-align: top;
            }
        #LISTTABLE table td, #LISTTABLE table th, #LISTTABLE table,
        #LinkTable table, #LinkTable table td{
            border: 1px solid gray;
            text-align: center;
        }


    </style>
</head>
<body>

<h1>Friend Form - View 
<?php
echo $username;
?>'s Friends</h1>

<h3>Other pages to update your friends list</h3>
<ul>
<li><a href="friendRead.php">Update friends from a file.</a></li>
<li><a href="friendAdd.php">Add another friend's info by hand.</a></li>
</ul>


<h3>Here is all of your friends</h3>
<div id="LISTTABLE">
<table>
    <?php
    include 'accountDB.php';
    $c = connect_accounts_DB();
    display_my_friends($c, $username);
    $c->close();
    ?>
</table>
</div>

<p id="demo"></p>

<script src="sortProfs.js"></script>
<script>
    var table_html = document.getElementById("LISTTABLE");
    console.log(table_html);

    // Note: I know there is a better way to add same action to multiple
    // objects where they don't all change/mess with the params of the
    // other listeners, yet this is sufficient for now.
    // TODO: use a loop to add the listeners (in case more cols are added).
    var headers = table_html.getElementsByTagName("th");
    console.log(headers);
    var h0 = headers[0].innerHTML;
    headers[0].addEventListener("click", function(){
            testing("LISTTABLE", h0, 0);
    });
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

<h4><a href="index.php">Home<a></h4>
<a href="logout.php">Logout</a>

</body>
</html>
