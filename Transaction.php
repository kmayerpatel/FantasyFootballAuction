<?php

class Transaction {
	private $owner;
	private $player;
	private $price;

	function __construct($from_json) {
		$this->owner = $from_json['owner'];
		$this->player = $from_json['player'];
		$this->price = $from_json['price'];
	}

	function asArray() {
		return array('owner' => $this->owner,
					 'player' => $this->player,
					 'price' => $this->price);
	}

	function asJSON() {
		return json_encode($this->asArray());
	}

	function getOwner() {
		return $this->owner;
	}

	function getPlayer() {
		return $this->player;
	}

	function getPrice() {
		return $this->price;
	}
}