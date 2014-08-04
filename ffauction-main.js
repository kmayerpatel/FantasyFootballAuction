var byeWeeks = {
    ARI: 4,  CIN: 4, CLE: 4, DEN: 4, SEA: 4, STL: 4,
    MIA: 5,  OAK: 5,
    KC: 6,   NO: 6,
    PHI: 7,  TB: 7,
    NYG: 8,  SF: 8,
    ATL: 9,  BUF: 9, CHI: 9, DET: 9, GB: 9, TEN: 9,
    HOU: 10, IND: 10, MIN: 10, NE: 10, SAN: 10, WAS: 10,
    BAL: 11, DAL: 11, JAC: 11, NYJ: 11, 
    CAR: 12, PIT: 12};

$(document).ready(function() {

    // Model objects

    var owners = [new Owner('Ketan'),
		  new Owner('Jamo'),
		  new Owner('Forbes'),
		  new Owner('CG'),
		  new Owner('Z'),
		  new Owner('Elder'),
		  new Owner('Los'),
		  new Owner('Singer'),
		  new Owner('Vince'),
		  new Owner("O'Malley"),
		  new Owner('Rich'),
		  new Owner('New Guy')];

    var transaction_log = new TransactionLog();

    var model = {owners: owners,
		 transaction_log: transaction_log
		};

    // View objects

    var block_ui = new BlockUI('block-ui');
    var auction_ui = new AuctionUI('auction-ui', owners);
    var last_transaction_ui = new LastTransactionUI('last-transaction-ui',
						    transaction_log);
    var roster_uis = {};
    for (var i=0; i<owners.length; i++) {
	var next_roster_ui = new RosterUI(owners[i], 'rosters');
	roster_uis[owners[i].name] = next_roster_ui;
    }

    var view = {block_ui: block_ui,
		last_transaction_ui: last_transaction_ui,
		roster_uis: roster_uis,
		auction_ui: auction_ui
	       };

    // Controller

    var controller = new FFAuctionController(model);

    block_ui.show();
    last_transaction_ui.show();
    auction_ui.hide();
});

var FFAuctionController = function(model) {
    this.model = model;
};

