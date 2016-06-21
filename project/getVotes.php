<?php
include 'storyDB.php';

$c = connect_DB();

// get the id of the requested story
$story_id = $_REQUEST['story_id'];
$event_id = $_REQUEST['event_id'];
// If the choice_id is set, then go ahead and submit it
if(isset($_REQUEST['choice_id'])) {
    $choice_id = $_REQUEST['choice_id'];
    submit_vote($c, $story_id, $event_id, $choice_id);
} // otherwise, don't submit. just display results

// Display the current vote totals
display_event_votes($c, $story_id, $event_id);

$c->close();

?>
