<?php

require_once 'FFAuctionConstants.php';
require_once 'Auction.php';
require_once 'Roster.php';
require_once 'Transaction.php';

class AuctionState {
	private $rosters;
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
		$this->rosters = array();
		foreach (FFAuctionConstants::owners() as $owner_name) {
			$this->rosters[$owner_name] = new Roster();
		}

		if ($from_json == null) {
			$this->current_auction = null;
			$this->next_to_pick = 0;
			$this->version = 0;
			$this->transactions = array();
			$this->timestamp = time();
		} else {
			if ($from_json['current_auction'] != null) {
				$this->current_auction = new Auction($from_json['current_auction']);
			} else {
				$this->current_auction = null;
			}
			$this->next_to_pick = $from_json['next_to_pick'];
			$this->version = $from_json['version'];
			$this->transactions = array();
			foreach($from_json['transactions'] as $transaction_data) {
				$t = new Transaction($transaction_data);
				$this->transactions[] = $t;
				$this->rosters[$t->getOwner()]->addToRoster($t);
			}
			$this->timestamp = $from_json['timestamp'];
		}
	}

	function asJSON() {
		return json_encode($this->asArray());
	}

	function asArray() {
		$current_auction = null;
		if ($this->inAuction()) {
			$current_auction = $this->current_auction->asArray();
		}

		$transaction_array = array();
		foreach ($this->transactions as $t) {
			$transaction_array[] = $t->asArray();
		}

		return array(
			'current_auction' => $current_auction,
			'next_to_pick' => $this->next_to_pick,
			'version' => $this->version,
			'transactions' => $transaction_array,
			'timestamp' => $this->timestamp
			);
	}

	function inAuction() {
		return ($this->current_auction != null);
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

		/* Need to do this in case nomination comes from commissioner. */
		$nominator = $this->nextToNominate();

		$this->current_auction = Auction::create($nominator, $nomination);

		$this->log_event('AuctionStart', array('nominator' => $nominator,
				                               'nomination' => $nomination,
							   				   'timestamp' => $this->current_auction->getTimestamp()));
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

	function cancelBid($bidder, $bid, $timestamp) {
		if (!$this->inAuction() ||
			$this->current_auction->cancelBid($bidder, $bid, $timestamp) == false) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->log_event('CancelBid',
			array('auction_timestamp' => $timestamp,
				  'bidder' => $bidder,
				  'bid' => $bid));
	}

	function goingOnce($timestamp) {
		if (!$this->inAuction() ||
			!$this->current_auction->goingOnce($timestamp)) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->log_event('GoingOnce', array('auction_timestamp' => $timestamp));
		return true;
	}

	function goingTwice($timestamp) {
		if (!$this->inAuction() ||
			!$this->current_auction->goingTwice($timestamp)) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->log_event('GoingTwice', array('auction_timestamp' => $timestamp));
		return true;
	}

	function sold($timestamp) {
		if (!$this->inAuction() ||
			$this->current_auction->getTimestamp() != $timestamp ||
			$this->current_auction->getStatus() != 'Going twice') {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$transaction = new Transaction(array('owner' => $this->current_auction->highestBidder(),
											 'player' => $this->current_auction->getNomination(),
											 'price' => $this->current_auction->highestBid()));
		$this->current_auction = null;
		$this->rosters[$transaction->getOwner()]->addToRoster($transaction);
		$this->transactions[] = $transaction;

		$last_to_pick = $this->next_to_pick;
		$owners = FFAuctionConstants::owners();
		$this->next_to_pick = ($this->next_to_pick + 1)%count($owners);
		while ($this->next_to_pick != $last_to_pick) {
			if ($this->rosters[$owners[$this->next_to_pick]]->canNominate()) {
				break;
			}
		}
		$this->log_event('Transaction', $transaction->asArray());
		return true;
	}

	function cancelAuction($timestamp) {
		if (!$this->inAuction() ||
			$timestamp != $this->current_auction->getTimestamp()) {
			header('HTTP/1.1 403 Forbidden');
			exit();
		}

		$this->current_auction = null;
		$this->log_event('CancelAuction', $timestamp);
		return true;
	}

	function undoLastTransaction($owner, $player_name) {

		$last = end($this->transactions);

		if ($this->inAuction() ||
			count($this->transactions) < 1 ||
			$last->getOwner() != $owner ||
			($last->getPlayer())['name'] != $player_name) {
			header('HTTP/1.1 403 Forbidden');
			exit();			
		}

		pop($this->transactions);
		/* If last transaction, should be last on owner's roster as well. */
		$this->rosters[$owner]->removeFromRoster($last);

		return true;
	}

	function log_event($event_type, $event_data) {
		$event_num = $this->advanceVersion();

		$event = array('type' => $event_type,
			'data' => $event_data);

		file_put_contents(FFAuctionConstants::STATE_LOG_FILE_LOCATION, "".$event_num.":".json_encode($event)."\n", FILE_APPEND);
		$this->save();
	}
}
