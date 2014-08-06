<?php
session_start();

require_once 'AuctionState.php';

if (!isset($_REQUEST['owner']) ||
	!isset($_REQUEST['player_name'])) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

$auction_state = AuctionState::load();
$auction_state->undoLastTransaction($_REQUEST['owner'], $_REQUEST['player_name']);

header("Content-type: application/json");
print(json_encode(true));
