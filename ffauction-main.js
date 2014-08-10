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

var auctionStatusVersion;
var auctionStatusTimestamp;

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
		  new Owner('Terrence')];

    Owner.owners = owners;
    
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

    block_ui.show();
    last_transaction_ui.show();
    auction_ui.hide();

    var handleStateUpdate = function (auction_status) {

        auctionStatusVersion = auction_status.version;
        auctionStatusTimestamp = auction_status.timestamp;

        for (var i=0; i<Owner.owners.length; i++) {
            Owner.owners[i].clear();
        }
        last_transaction_ui.clear();

        for (var i=0; i<auction_status.transactions.length; i++) {
            var t = auction_status.transactions[i];
            var t_owner = Owner.lookup(t.owner);
            var t_player = new Player(t.player.name, t.player.position, t.player.team);
            var t_price = t.price;
            var transaction = new Transaction(t_player, t_price, t_owner);
            transaction_log.push(transaction);
            t_owner.addToRoster(transaction);
        }        

        if (auction_status.current_auction != null) {
            var auction = new Auction(new Player (auction_status.current_auction.nomination.name,
                                                  auction_status.current_auction.nomination.position,
                                                  auction_status.current_auction.nomination.team),
                                        auction_status.current_auction.timestamp);
            auction_ui.setAuction(auction);

            for (var i=0; i<auction_status.current_auction.bids.length; i++) {
                var next_bid = auction_status.current_auction.bids[i];
                auction.confirmBid(Owner.lookup(next_bid.bidder), next_bid.bid, auction.timestamp);
            }
            if (auction_status.current_auction.status == "Running") {
                auction.setStatus(Auction.Status.UNDERWAY);
            } else if (auction_status.current_auction.status == "Going once") {
                auction.setStatus(Auction.Status.GOING_ONCE)
            } else if (auction_status.current_auction.status == "Going twice") {
                auction.setStatus(Auction.Status.GOING_TWICE)
            } 
            block_ui.hide();
            last_transaction_ui.hide();
            auction_ui.show();
            $('#bid-history-div').css({scrollTop:  $('#bid-history').prop('scrollHeight')});

        } else {
            block_ui.show();
            last_transaction_ui.show();
            auction_ui.hide();
        }

        setTimeout(function () {
            $.get("auction-state.php", null, handleStateUpdate, 'json');
        }, 200);
    }

    $.get("auction-state.php", null, handleStateUpdate, 'json');
});