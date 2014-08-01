<?php

function state_file_location() {
	return "auction_state.txt";
}

function owners() {
	return array('Ketan', 'Jamo', 'Forbes', 'CG', 'Z', 'Los', 'Elder', 'Singer', 'Vince', 
	            "O'Malley", 'Rich', 'Terrence');
}

function load_auction_state() {

	if (file_exists(state_file_location())) {
		return json_decode(file_get_contents($auction_state_file), true);
	} else {
		return null;
	}
}
