<?php

class Auction {
	private $timestamp;
	private $nominator;
	private $nomination;
	private $bids;
	private $highest_bidder;
	private $highest_bid;
	private $status;

	function __construct($from_json) {
		$this->timestamp = $from_json['timestamp'];
		$this->nominator = $from_json['nominator'];
		$this->nomination = $from_json['nomination'];
		$this->bids = $from_json['bids'];
		$this->highest_bidder = $from_json['highest_bidder'];
		$this->highest_bid = $from_json['highest_bid'];
		$this->status = $from_json['status'];
	}

	function getTimestamp() {
		return $this->timestamp;
	}

	function highestBidder() {
		return $this->highest_bidder;
	}

	function highestBid() {
		return $this->highest_bid;
	}

	function getStatus() {
		return $this->status();
	}

	function getNominator() {
		return $this->nominator;
	}

	function getNomination() {
		return $this->nomination;
	}

	function bid($bidder, $bid, $timestamp) {
		if (($this->timestamp != $timestamp) ||
			($bid <= $this->highest_bid)) {
			return false;
		}

		$this->bids[] = array('bidder' => $bidder, 'bid' => $bid);
		$this->highest_bidder = $bidder;
		$this->highest_bid = $bid;
		return true;
	}

	function cancelBid($bidder, $bid, $timestamp) {
		if (($timestamp != $this->timestamp)||
			($bid != $this->highest_bid) ||
			($bidder != $this->highest_bidder) ||
			(count($this->bids) == 1)) {
			return false;
		}

		array_pop($this->bids);
		$new_highest = end($this->bids);
		$this->highest_bidder = $new_highest['bidder'];
		$this->highest_bid = $new_highest['bid'];
		return true;
	}

	function goingOnce() {
		if ($this->status != 'Running') {
			return false;
		}
		$this->status = 'Going once';
		return true;
	}
	
	function asArray() {
		return array('timestamp' => $this->timestamp,
			         'nominator' => $this->nominator,
			         'nomination' => $this->nomination,
					 'bids' => $this->bids,
					 'highest_bidder' => $this->highest_bidder,
					 'highest_bid' => $this->highest_bid,
					 'status' => $this->status);
	}

	static function create($nominator, $nomination) {
		return new Auction(array('timestamp' => time(),
			                     'nominator' => $nominator,
			                     'nomination' => $nomination,
								 'bids' => array(array('bidder' => $nominator, 'bid' => 1)),
								 'highest_bidder' => $nominator,
								 'highest_bid' => 1,
								 'status' => 'Running'));
	}
}
