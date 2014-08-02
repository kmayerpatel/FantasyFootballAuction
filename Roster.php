<?php

require_once 'FFAuctionConstants.php';
require_once 'Transaction.php';

class Roster {
	private $team;

	function __construct($roster_data = null) {
		$this->team = array();
		if ($roster_data != null) {
			foreach($roster_data as $transaction) {
				$this->team[] = new Transaction($transaction);
			}
		}
	}

	function asJSON() {
		return json_encode($this->asArray());
	}

	function asArray() {
		$roster_json_array = array();
		foreach($this->team as $transaction) {
			$roster_json_array[] = $transaction->asArray();
		}
		return $roster_json_array;
	}

	function addToRoster($transaction) {
		$this->team[] = $transaction;
	}

	function rosterSize() {
		return count($this->team);
	}

	function payroll() {
		$sum = 0;
		foreach($this->team as $t) {
			$sum += $team->getPrice();
		}
		return $sum;
	}

	function canNominate() {
		return ($this->getMaxBid() > 0) && ($this->rosterSize() < FFAuctionConstants::MAX_ROSTER_SIZE);
	}

	function getMaxBid() {
		$min_roster_budget = FFAuctionConstants::MIN_ROSTER_SIZE - $this->rosterSize();
		if ($min_roster_budget < 0) {
			$min_roster_budget = 0;
		}

		return (FFAuctionConstants::SALARY_CAP - $this->payroll() - $min_roster_budget + 1);
	}
}