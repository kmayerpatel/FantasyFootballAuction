<?php
session_start();

require_once 'ffauction-lib.php';

header('Content-type: application/json');
print(file_get_contents(state_file_location()));
?>