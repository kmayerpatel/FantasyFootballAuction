<?php
session_start();

$_SESSION['pick_idx'] = 0;

require_once 'Owners.php';
require_once 'Teams.php';

$auction_state = array();

$auction_state['rosters'] = array();
$rosters = $auction_state['rosters'];
foreach ($owners as $owner_name) {
	$rosters[$owner_name] = array();
}
$auction_state['cap'] = 100;
$auction_state['current_auction'] = null;
$auction_state['next_to_pick'] = $owners[0];

$auction_state_json = json_encode($auction_state);

header('Content-type: text/plain');
if (file_put_contents('auction_state.txt', $auction_state_json) === false) {
	print("File put contents fails.\n");
} else {
	print("Auction reset. Init JSON as follows:");
}
print($auction_state_json);
?>
