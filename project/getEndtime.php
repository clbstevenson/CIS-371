<?php
// getEndtime.php
// Requests the endtime for the current event of the story from the DB
// and then returns, to be used by a jQuery countdown timer.
include 'storyDB.php';

$c = connect_DB();

// get the id of the requested story
$story_id = $_REQUEST['story_id'];
$event_id = $_REQUEST['event_id'];
$endtime = get_story_event_endtime($c, $story_id, $event_id);
//echo "endtime: $endtime";
//echo "\n";
$timestamp = date_create_from_format('d/m/Y H:i:s', $endtime);
//echo "timestamp: " . $timestamp;
//echo date_format($timestamp, 'Y-m-d H:i:s');
echo $endtime;

/*
try {
    //$date = new DateTime($endtime);
    echo $date->format("M j, Y H:i:s O")."\n";
} catch(Exception $e) {
    echo $e->getMessage();
    exit(1);
}
*/
//echo $endtime."\n";
//submit_vote($c, $story_id, $event_id, $choice_id);
//display_event_votes($c, $story_id, $event_id);

$c->close();

?>
