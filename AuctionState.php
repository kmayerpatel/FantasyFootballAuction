<?php

require_once 'FFAuctionConstants.php';
require_once 'Auction.php';
require_once 'Roster.php';
require_once 'Transaction.php';

class AuctionState {
	private $rosters;
	private $cap;
	private $current_auction;
	private $next_to_pick;
	private $version;
	private $transactions;
	private $timestamp;

	static function load() {
		$filename = FFAuctionConstants::STATE_FILE_LOCATION;
		if (file_exists($filename)) {
			return new AuctionState(json_decode(file_get_contents($filename), true));
		} else {
			return null;
		}
	}

	function __construct($from_json = null) {
		if ($from_json == null) {
			$this->rosters = array();
			foreach (FFAuctionConstants::owners() as $owner_name) {
				$this->rosters[$owner_name] = new Roster();
			}
			$this->cap = 100;
			$this->current_auction = null;
			$this->next_to_pick = 0;
			$this->version = 0;
			$this->transactions = array();
			$this->timestamp = time();
		} else {
			$this->rosters = array();
			foreach($from_json['rosters'] as $owner => $roster_data) {
				$this->rosters[$owner] = new Roster($roster_data);
			}
			$this->cap = $from_json['cap'];
			if ($from_json['current_auction'] != null) {
				$this->current_auction = new Auction($from_json['current_auction']);
			} else {
				$this->current_auction = null;
			}
			$this->next_to_pick = $from_json['next_to_pick'];
			$this->version = $from_json['version'];
			$this->transactions = array();
			foreach($from_json['transactions'] as $transaction_data) {
				$this->transactions[] = new Transaction($transaction_data);
			}
			$this->timestamp = $from_json['timestamp'];
		}
	}

	function asJSON() {
		return json_encode($this->asArray());
	}

	function asArray() {
		$roster_array = array();
		foreach ($this->rosters as $owner => $roster) {
			$roster_array[$owner] = $roster->asArray();
		}

		$current_auction = null;
		if ($this->inAuction()) {
			$current_auction = $this->current_auction->asArray();
		}

		$transaction_array = array();
		foreach ($this->transactions as $t) {
			$transaction_array[] = $t->asArray();
		}

		return array(
			'rosters' => $roster_array,
			'cap' => $this->cap,
			'current_auction' => $current_auction,
			'next_to_pick' => $this->next_to_pick,
			'version' => $this->version,
			'transactions' => $transaction_array,
			'timestamp' => $this->timestamp
			);
	}

	function inAuction() {
		return ($this->current_auciton != null);
	}

	function nextToNominate() {
		$owners = FFAuctionConstants::owners();
		return $owners[$this->next_to_pick];
	}

	function start_auction($nominator, $nomination) {
		if (($this->inAuction()) || (($nominator != "commish") && ($this->nextToNominate() != $nominator))) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$nominator = $this->nextToNominate();
		$this->current_auction = Auction::create($nominator, $nomination);

		$this->log_event('AuctionStart', array('nominator' => $nominator,
				                               'nomination' => $nomination,
							   				   'timestamp' => $auction_start));
	}

	function advanceVersion() {
		$current_version = $this->version;
		$this->version += 1;
		return $current_version;
	}

	function getTimestamp() {
		return $this->timestamp;
	}

	function save() {
		if (file_put_contents(FFAuctionConstants::STATE_FILE_LOCATION, $this->asJSON()) === false) {
			return false;
		}
		return true;
	}

	function bid($bidder, $bid, $timestamp) {

		if (!$this->inAuction() ||
			$bid > $this->rosters[$bidder]->getMaxBid() ||
			$this->current_auction->bid($bidder, $bid, $timestamp) == false) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->log_event('Bid',
			array('auction_timestamp' => $timestamp,
				  'bidder' => $bidder,
				  'bid' => $bid));
	}

	function log_event($event_type, $event_data) {
		$event_num = $this->advanceVersion();

		$event = array('type' => $event_type,
			'data' => $event_data);

		file_put_contents(FFAuctionConstants::STATE_LOG_FILE_LOCATION, "".$event_num.":".json_encode($event)."\n", FILE_APPEND);
		$this->save();
	}
}
