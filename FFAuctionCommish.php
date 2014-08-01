<!DOCTYPE html>
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
  
<div class="row" id="auction-ui">
  <div class="col-md-6">

    <div class="row">
      <div class="col-md-12" id="onblock-div">
        <h2>On The Block:</h2>
        <div class="onblock">
	  <span id="auction-ui-player" class="h3">Aaron Rodgers (QB, GB)</span>
        </div>
	<div>
	  <button id="auction-ui-cancel" type="button" class="btn btn-xs btn-danger">Cancel Auction</button>
	  <hr>
	</div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12" id="bid-cntrls">

        <div class="row" id="bid-cntrls-row1">
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input"  type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	</div>

	<div class="row" id="bid-cntrls-row2">
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input"  type="text" size="3">
	  </div>
	</div>
	
	<div class="row" id="bid-cntrls-row3">
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	</div>

	<div class="row" id="bid-cntrls-row4">
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	  <div class="col-md-4 bid-cntrl">
	    <button type="button" class="btn btn-default bid-btn"></button>
	    <input class="bid-input" type="text" size="3">
	  </div>
	</div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
	<div id="auction-ui-message" class="h3">
	  Going once...
	</div>
      </div>
    </div>	
  </div>

  <div class="col-md-6">
    <div class="row">
      <div class="col-md-12" id="bid-info">
	<h2>Winning Bid:</h2>
	<div class="winning-bid">
	  <span id="auction-ui-winning-bid" class="h3">Jamo for $23</span>
	</div>
	<h4>Bid History:</h4>
	<div id="bid-history-div">
	  <table id="bid-history" class="table table-striped table-condensed">
	    <tbody>
	    <tr class="bid-history-row"><td>Z</td><td>$9</td></tr>
	    <tr class="bid-history-row"><td>Jamo</td><td>$10</td></tr>
	    <tr class="bid-history-row"><td>Ketan</td><td>$20</td></tr>
	    <tr class="bid-history-row"><td>Jamo</td><td>$23</td></tr>
	    </tbody>
	  </table>
	</div>
	<button type="button" class="btn btn-xs btn-danger" id="auction-ui-retract">Retract Last Bid</button></form>
      </div>
    </div>
  </div>
</div>
    
<div class="row">
  <div class="col-md-6" id="block-ui">
    <h2>Next on block:</h2>
    <form class="form-horizontal" role="form">

    <div class="form-group">
      <label for="blockPlayerName" class="col-md-2 control-label">Name</label>
      <div class="col-md-10">
	<input type="text" class="form-control" id="blockPlayerName"
	       placeholder="Enter player name" name="playerName">
      </div>
    </div>
    
    <div class="form-group">
      <label for="blockPlayerTeam" class="col-md-2 control-label">Team</label>
      <div class="col-md-10">
        <select class="form-control" id="blockPlayerTeam" name="playerTeam">
	  <option value="ARI">Arizona Cardinals</option>
	  <option value="ATL">Atlanta Falcons</option>
	  <option value="BAL">Baltimore Ravens</option>
	  <option value="BUF">Buffalo Bills</option>
	  <option value="CAR">Carolina Panthers</option>
	  <option value="CHI">Chicago Bears</option>
	  <option value="CIN">Cincinnati Bengals</option>
	  <option value="CLE">Cleveland Browns</option>
	  <option value="DAL">Dallas Cowboys</option>
	  <option value="DEN">Denver Broncos</option>
	  <option value="DET">Detroit Lions</option>
	  <option value="GB">Green Bay Packers</option>
	  <option value="HOU">Houston Texans</option>
	  <option value="IND">Indianapolis Colts</option>
	  <option value="JAC">Jacksonville Jaguars</option>
	  <option value="KC">Kansas City Chiefs</option>
	  <option value="MIA">Miami Dolphins</option>
	  <option value="MIN">Minnesota Vikings</option>
	  <option value="NE">New England Patriots</option>
	  <option value="NO">New Orleans Saints</option>
	  <option value="NYG">New York Giants</option>
	  <option value="NYJ">New York Jets</option>
	  <option value="OAK">Oakland Raiders</option>
	  <option value="PHI">Philadelphia Eagles</option>
	  <option value="PIT">Pittsburgh Steelers</option>
	  <option value="SAN">San Diego Chargers</option>
	  <option value="SF">San Francisco 49ers</option>
	  <option value="SEA">Seattle Seahawks</option>
	  <option value="STL">St. Louis Rams</option>
	  <option value="TB">Tampa Bay Buccaneers</option>
	  <option value="TEN">Tennessee Titans</option>
	  <option value="WAS">Washington Redskins</option>
	</select>
      </div>
    </div>

    <div class="form-group">
      <label for="blockPlayerPosition" class="col-md-2 control-label">Position</label>      
      <div class="col-md-10">
	<select class="form-control" id="blockPlayerPosition" name="playerPosition">
	  <option value="QB">QB</option>
	  <option value="WR">WR</option>
	  <option value="RB">RB</option>
	  <option value="TE">TE</option>
	  <option value="K">K</option>
	  <option value="D">D</option>
	</select>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-1"></div>
      <div class="col-md-2">
        <button id="blockStartAuction" name="startAuction" type="button" class="btn btn-primary">
	  Start Auction
	</button>
      </div>
    </div>
    </form>
  </div>

  <div class="col-md-5" id="last-transaction-ui">
    <h2>Last Transaction:</h2>
    <div class="jumbotron">
    </div>
    <form>
      <button id="undo-transaction" type="button" class="btn btn-danger">Undo Last Transaction</button>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-md-12"><hr></div>
</div>

<div class="row">
  <div class="col-md-12">
    <div style="overflow: auto; width: 100%">
      <div class="row" style="width: 4000px" id="rosters">
      </div>
    </div>
  </div>
</div>

</div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="jquery-1.11.1.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
  <script src="player.js"></script>
  <script src="transaction.js"></script>
  <script src="owner.js"></script>
  <script src="block-ui.js"></script>
  <script src="roster-ui.js"></script>
  <script src="auction.js"></script>
  <script src="auction-ui.js"></script>
  <script src="ffauction-main.js"></script>
</body>

</html>
