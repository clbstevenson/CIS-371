<?php
// serverTime.php
// This file synchronizes the jQuery timers with the server times
$now = new DateTime();
echo $now->format("M j, Y H:i:s O")."\n";
?>
