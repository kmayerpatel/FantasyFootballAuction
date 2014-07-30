<?php
session_start();

require('Owner.php');

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Fantasy Football Auction</title>

  <!-- Bootstrap -->
  <link href="bootstrap-3.1.1-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="ffauction.css" rel="stylesheet">

</head>

<body>

<div class="container">
  
<div class="row">

<div class="col-md-offset-3 col-md-4">
    <h2>Who the hell are you?</h2>

    <form role="form">
      <div class="form-group">
<?php
    for($i=0; $i<