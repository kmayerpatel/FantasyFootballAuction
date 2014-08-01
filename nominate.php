<?php
session_start();

include 'ffauction-lib.php';

if (!isset($_REQUEST['nominator']) ||
	!isset($_REQUEST['name']) ||
	!isset($_REQUEST['position']) ||
	!isset($_REQUEST['team'])) {
	header("HTTP/1.1 400 Bad Request");
	exit();
}

