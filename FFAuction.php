<?php
session_start();

if (!isset($_POST['name']) ||
	!isset($_POST['commish'])) {

  header('Location: FFAuction-login.php');
  exit();
}

$_SESSION['name'] = $_POST['name'];
if ($_POST['commish'] == 'yes') {
/*	print("Yes"); */
	include 'FFAuctionCommish.php';
} else {
/*	print("No"); */
	include 'FFAuctionCommish.php';
}
