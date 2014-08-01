<?php
session_start();

include 'ffauction-lib.php';

if (!isset($_REQUEST['bidder']) ||
	!isset($_REQUEST['timestamp']) ||
	!isset($_REQUEST['bid'])) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

$auction_state = load_auction_state();
$auction_state->bid($_REQUEST['bidder'],
					intval($_REQUEST['bid']),
					intval($_REQUEST['timestamp']));

header("Content-type: application/json");
print(json_encode(true));
