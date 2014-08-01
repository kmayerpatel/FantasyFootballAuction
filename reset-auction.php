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

file_put_contets('auction_state.txt', json_encoder($auction_state));
?>
Auction Reset.