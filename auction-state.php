<?php
session_start();

require_once 'FFAuctionConstants.php';

header('Content-type: application/json');
print(file_get_contents(FFAuctionConstants::STATE_FILE_LOCATION));
?>