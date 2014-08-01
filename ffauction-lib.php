<?php

class AuctionState {
	private $data = array();

	function __construct($from_json) {
		if ($from_json == null) {
			$auction_state = array();

			$auction_state['rosters'] = array();
			$rosters = $auction_state['rosters'];
			foreach (owners() as $owner_name) {
				$rosters[$owner_name] = array();
			}
			$auction_state['cap'] = 100;
			$auction_state['current_auction'] = null;
			$auction_state['next_to_pick'] = 0;
			$auction_state['version'] = 0;
			$auction_state['transactions'] = array();
			$auction_state['init_time'] = time();

			$this->data = $auction_state;
		} else {
			$this->data = $from_json;
		}
	}

	function asJSON() {
		return json_encode($this->data);
	}

	function inAuction() {
		return ($this->data['current_auction'] != null);
	}

	function nextToNominate() {
		$owners = owners();
		return ($owners[$this->data['next_to_pick']]);
	}

	function start_auction($nominator, $nomination) {
		if (($this->inAuction()) || (($nominator != "commish") && ($this->nextToNominate() != $nominator))) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->data['current_auction'] = array('nominator' => $nominator,
			'nomination' => $nomination,
			'bids' => array());

		$this->log_event('AuctionStart',
			array('nominator' => $nominator,
				'nomination' => $nomination));
	}

	function advance_version() {
		$current_version = $this->data['version'];
		$this->data['version'] += 1;
		return $current_version;
	}

	function timestamp() {
		return $this->data['init_time'];
	}
	
	function save() {
		if (file_put_contents(state_file_location(), $this->asJSON()) === false) {
			return false;
		}
		return true;
	}

	function log_event($event_type, $event_data) {
		$event_num = $this->advance_version();

		$event = array('type' => $event_type,
			'data' => $event_data);

		file_put_contents(state_log_file_location(), "".$event_num.":".json_encode($event)."\n", FILE_APPEND);
		$this->save();
	}
}

function state_file_location() {
	return "auction_state.txt";
}

function state_log_file_location() {
	return "auction_state_log.txt";
}

function owners() {
	return array('Ketan', 'Jamo', 'Forbes', 'CG', 'Z', 'Los', 'Elder', 'Singer', 'Vince', 
		"O'Malley", 'Rich', 'Terrence');
}

function load_auction_state() {

	$filename = state_file_location();
	if (file_exists($filename)) {
		return new AuctionState(json_decode(file_get_contents($filename), true));
	} else {
		return null;
	}
}

