<?php
session_start();
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
<form role="form">

<div class="container">
  
<div class="row">
<div class="col-md-offset-2 col-md-8">
    <h2>Who the hell are you?</h2>

      <div class="form-group">
        <div class="row">
          <div class="col-md-4">
        <label>Ketan
        <input type="radio" name="name" value="Ketan"></label><br>
        <label>Jamo
        <input type="radio" name="name" value="Jamo"></label><br>
        <label>Forbes
        <input type="radio" name="name" value="Forbes"></label><br>
        <label>CG
        <input type="radio" name="name" value="CG"></label><br>
      </div>
      <div class="col-md-4">
        <label>Z
        <input type="radio" name="name" value="Z"></label><br>
        <label>Elder
        <input type="radio" name="name" value="Elder"></label><br>
        <label>Los
        <input type="radio" name="name" value="Los"></label><br>
        <label>Singer
        <input type="radio" name="name" value="Singer"></label><br>
      </div>
      <div class="col-md-4">
        <label>Vince
        <input type="radio" name="name" value="Vince"></label><br>
        <label>O'Malley
        <input type="radio" name="name" value="O'Malley"></label><br>
        <label>Rich
        <input type="radio" name="name" value="Rich"></label><br>
        <label>Terrence
        <input type="radio" name="name" value="Terrence"></label><br>
      </div>
      </div>
    </div> 
  </div>
</div>

<div class="row">
<div class="col-md-offset-2 col-md-8">

  <h2>Do you want commissioner interface?</h2>
    <div class="form-group">
      <label>Yes
        <input type="radio" name="commish" value="yes"></label><br>
      <label>No
        <input type="radio" name="commish" value="no"></label><br>
    </div>  
</div>

<div class="row">
<div class="col-md-offset-2 col-md-8">
  <button type="submit" class="btn btn-default">Go To Auction</button>
</form>
</body> 
</html>