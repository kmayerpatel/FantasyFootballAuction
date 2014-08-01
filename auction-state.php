<?php
session_start();

$auction_state_json = file_get_contents('auction_state.txt');
header('Content-type: application/json');
print($auction_state_json);
