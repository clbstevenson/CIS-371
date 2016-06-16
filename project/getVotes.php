<?php
include 'storyDB.php';

$c = connect_DB();

// get the id of the requested story
$story_id = $_REQUEST['story_id'];
$event_id = $_REQUEST['event_id'];
$choice_id = $_REQUEST['choice_id'];
//display_story_history($c, $id);
submit_vote($c, $story_id, $event_id, $choice_id);
display_event_votes($c, $story_id, $event_id);

$c->close();

?>
