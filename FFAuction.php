<?php

session_start();

if (!isset($_SESSION['id'])) {
  header('Location: FFAuction-login.php');
  exit();
}

