<?php
session_start();

include 'ffauction-lib.php';

if (!isset($_REQUEST['nominator']) ||
	!isset($_REQUEST['name']) ||
	!isset($_REQUEST['position']) ||
	!isset($_REQUEST['team'])) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

$nominator = $_REQUEST['nominator'];
$nomination = array('name' => $_REQUEST['name'],
	'position' => $_REQUEST['position'],
	'team' => $_REQUEST['team']);

$auction_state = load_auction_state();

$auction_state->start_auction($nominator, $nomination);

header("Content-type: application/json");
print(json_encode(true));

