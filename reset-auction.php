<?php
$auction_state_file = 'auction_state.txt';

/* Save old auction state in back up directory */


if (file_exists($auction_state_file)) {
	$backup_dir = 'backups';
	$auction_state_json = file_get_contents($auction_state_file);
	$auction_state = json_decode($auction_state_json, true);
	file_put_contents($backup_dir.'/'.$auction_state['init_time'].'.txt', $auction_state_json);
	file_put_contents($backup_dir.'/last.txt', $auction_state_json);
}

require_once 'Owners.php';

$auction_state = array();

$auction_state['rosters'] = array();
$rosters = $auction_state['rosters'];
foreach ($owners as $owner_name) {
	$rosters[$owner_name] = array();
}
$auction_state['cap'] = 100;
$auction_state['current_auction'] = null;
$auction_state['next_to_pick'] = 0;
$auction_state['version'] = 0;
$auction_state['transactions'] = array();
$auction_state['init_time'] = time();

$auction_state_json = json_encode($auction_state);

header('Content-type: text/plain');
if (file_put_contents('auction_state.txt', $auction_state_json) === false) {
	print("File put contents fails.\n");
} else {
	print("Auction reset. Init JSON as follows:\n");
}
print($auction_state_json);
?>
