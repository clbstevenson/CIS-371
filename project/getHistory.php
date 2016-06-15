<?php
include 'storyDB.php';

$c = connect_DB();

// get the id of the requested story
$id = $_REQUEST['id'];
display_story_history($c, $id);

?>
