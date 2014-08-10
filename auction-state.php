<?php
session_start();

require_once 'AuctionState.php';

$ts = -1;
if (isset($_REQUEST['timestamp'])) {
	$ts = intval($_REQUEST['timestamp']);;
}

$auction_state = AuctionState::load();

header("Content-type: application/json");
if ($auction_state->getTimestamp() <= $ts) {
	print(json_encode(false));
} else {
	print($auction_state->asJSON());
}
