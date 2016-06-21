<?php
// calcVotes.php
// Tallies all of the votes and determines a winner.
// The story and current event is then updated.
include 'storyDB.php';

$c = connect_DB();

// get the id of the requested story
$story_id = $_REQUEST['story_id'];
$event_id = $_REQUEST['event_id'];
// If the choice_id is set, then go ahead and submit it

$winner = calculate_votes($c, $story_id, $event_id);
if($winner) {
    //echo "Winner: $winner";
    //header: ("Location: story.php");
}
//header("Refresh:0; url=viewStories.php");

// Add the winner to the story_event table
insert_story_event($c, $story_id, $winner);
// Return the winner's ID so javascript can update some state variables
echo $winner;

$c->close();

?>
