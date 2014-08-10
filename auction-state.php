<?php
session_start();

require_once 'AuctionState.php';

$v = -1;
if (isset($_REQUEST['version'])) {
	$v = intval($_REQUEST['version']);;
}

$auction_state = AuctionState::load();

header("Content-type: application/json");
if ($auction_state->getVersion() <= $v) {
	print(json_encode(false));
} else {
	print($auction_state->asJSON());
}
