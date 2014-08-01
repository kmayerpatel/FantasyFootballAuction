<?php
session_start();

if (!isset($_SESSION['name']) && !isset($_POST['name'])) {
	header('Location: FFAuction-login.php');
  	exit();
}

if (isset($_POST['name'])) {
	$_SESSION['name'] = $_POST['name'];
}

if (isset($_POST['commish']) && $_POST['commish'] == 'yes') {
/*	print("Yes"); */
	include 'FFAuctionCommish.php';
} else {
/*	print("No"); */
	include 'FFAuctionCommish.php';
}
