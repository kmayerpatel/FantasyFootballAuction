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
			($bid <= $this->higest_bid)) {
			return false;
		}

		$this->bids[] = array('bidder' => $bidder, 'bid' => $bid);
		$this->highest_bidder = $bidder;
		$this->highest_bid = $bid;
		return true;
	}

	static function create($nominator, $nomination) {
		return new Auction(array('timestamp' => time(),
			                     'nominator' => $nominator,
			                     'nomination' => $nomination,
								 'bids' => array(array('bidder' => $nominator, 'bid' => 1)),
								 'highest_bidder' => $nominator,
								 'highest_bid' => 1,
								 'status' => 'running'));
	}
}
