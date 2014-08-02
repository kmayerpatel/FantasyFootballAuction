<?php
session_start();

require_once 'AuctionState.php';

if (!isset($_REQUEST['timestamp'])) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

$auction_state = AuctionState::load();
$auction_state->sold($_REQUEST['timestamp']);

header("Content-type: application/json");
print(json_encode(true));
