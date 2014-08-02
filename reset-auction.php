<?php

require_once 'FFAuctionConstants.php';
require_once 'AuctionState.php';

$auction_state = AuctionState::load();

if ($auction_state != null) {
	$backup_dir = 'backups';
	$auction_state_json = $auction_state->asJSON();
	file_put_contents($backup_dir.'/'.$auction_state->getTimestamp().'.txt', $auction_state_json);
	file_put_contents($backup_dir.'/last.txt', $auction_state_json);
}

$auction_state = new AuctionState(null);

header('Content-type: text/plain');
if (!$auction_state->save()) {
	print("File put contents fails.\n");
} else {
	file_put_contents(state_log_file_location(), "");
	print("Auction reset. Init JSON as follows:\n");
}
print($auction_state->asJSON());
?>
