<?php
session_start();

require_once 'AuctionState.php';

$auction_state = AuctionState::load();
if (!$auction_state->goingTwice()) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

header("Content-type: application/json");
print(json_encode(true));
