<?php

function state_file_location() {
	return "auction_state.txt";
}

function owners() {
	return array('Ketan', 'Jamo', 'Forbes', 'CG', 'Z', 'Los', 'Elder', 'Singer', 'Vince', 
	            "O'Malley", 'Rich', 'Terrence');
}

function load_auction_state() {

	$filename = state_file_location();
	if (file_exists($filename)) {
		return json_decode(file_get_contents($filename), true);
	} else {
		return null;
	}
}
