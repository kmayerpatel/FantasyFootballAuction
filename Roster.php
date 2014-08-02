<?php

require_once 'FFAuctionConstants.php'
require_once 'Transaction.php';

class Roster {
	private $team;

	function __construct($roster_data = null) {
		$team = array();
		if ($roster_data != null) {
			foreach($roster_data as $transaction) {
				$team[] = new Transaction($transaction);
			}
		}
	}

	function asJSON() {
		$roster_json = array();
		foreach($team as $transaction) {
			$roster_json[] = $transaction->asJSON();
		}
		return json_encode($roster_json);
	}

	function addToRoster($transaction) {
		$team[] = $transaction;
	}

	function rosterSize() {
		return count($team);
	}

	function getMaxBid() {
		$min_roster_budget = FFAuctionConstants::MIN_ROSTER_SIZE - $this->rosterSize();
		if ($min_roster_budget < 0) {
			$min_roster_budget = 0;
		}

		return (FFAuctionConstants::SALARY_CAP - $this->payroll() - $min_roster_budget + 1);
	}
}