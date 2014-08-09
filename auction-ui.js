var AuctionUI = function(div_id, owners) {
    this.div = $('#'+div_id);
    this.auction = null;

    var cancel_btn = this.div.find('#auction-ui-cancel');
    cancel_btn.on('click', this, function(e) {
	   var ui = e.data;
       if (ui.auction != null) {
            $.get("cancel-auction.php", {timestamp: ui.auction.timestamp});
       }
    });

    var retract_btn = this.div.find('#auction-ui-retract');
    retract_btn.on('click', this, function(e) {
	   var ui = e.data;
	   if (ui.auction != null) {
	       ui.auction.proposeRetraction();
	   }
    });

    var bid_cntrls = this.div.find('.bid-cntrl');
    this.bid_uis = [];

    for (var i=0; i<owners.length; i++) {
	   this.bid_uis.push(new BidUI($(bid_cntrls[i]), owners[i], this));
    }

    var auction_control_button = this.div.find('#auction-control');

    var this_auction_ui = this;
    auction_control_button.click(function (e) {
        var action = $(this).data('action');
        var ts = this_auction_ui.auction.timestamp;

        $.get(action, {
            timestamp: ts
        });
    });
}

AuctionUI.prototype.setMinBid = function (min_bid) {
    for (var i=0; i<this.bid_uis.length; i++) {
	this.bid_uis[i].setMinBid(min_bid);
    }
}
    
AuctionUI.prototype.clear = function() {
    this.div.find('#auction-ui-player').empty();
    this.div.find('#bid-cntrls .bid-input').val('');
    this.div.find('#auction-ui-message').empty();
    this.div.find('#auction-ui-winning-bid').empty();
    this.div.find('#bid-history .bid-history-row').remove();
}

AuctionUI.prototype.setAuction = function(auction) {
    if (this.auction != null) {
	   this.auction.unregisterObserver(this);
    }

    this.auction = auction;
    this.auction.registerObserver(this);
    
    this.clear();

    var player = this.auction.player;
    this.div.find('#auction-ui-player').text(player.name + 
					     ' (' + player.position + ', ' + player.team + ')');
    this.setMinBid(1);

    this.update_message();
}

AuctionUI.prototype.update_message = function() {
    var message = this.div.find('#auction-ui-message');
    var auction_control_button = this.div.find('#auction-control');

    switch(this.auction.status) {
    case Auction.Status.NOT_STARTED:
	message.text("Waiting for first bid...");
    auction_control_button.html("Going once...")
    auction_control_button.attr("disabled", "disabled");
    auction_control_button.data('action', 'going-once.php');
	break;
    case Auction.Status.UNDERWAY:
	message.text("Bidding underway.");
    auction_control_button.html("Going once...")
    auction_control_button.removeAttr("disabled");
    auction_control_button.data('action', 'going-once.php');
	break;
    case Auction.Status.GOING_ONCE:
	message.text("Going once...");
    auction_control_button.html("Going twice...")
    auction_control_button.removeAttr("disabled");
    auction_control_button.data('action', 'going-twice.php');
	break;
    case Auction.Status.GOING_TWICE:
	message.text("Going twice...");
    auction_control_button.html("Sold!")
    auction_control_button.removeAttr("disabled");
    auction_control_button.data('action', 'sold.php');
	break;
    case Auction.Status.SOLD:
	message.text("SOLD!");
    auction_control_button.html("Sold!")
    auction_control_button.attr("disabled", "disabled");
    auction_control_button.data('action', 'sold.php');
	break;
    case Auction.Status.CANCELLED:
	message.text("Auction cancelled.");
    auction_control_button.attr("disabled", "disabled");
	break;
    }
}

AuctionUI.prototype.auctionUpdate = function (auction, auction_event) {
    if (auction != this.auction) {
	   return;
    }

    if (auction_event.type == Auction.EventType.STATUS_CHANGE) {
	   this.update_message();
    } else if (auction_event.type == Auction.EventType.BID) {
	   this.enter_bid(auction_event.data);
    } else if (auction_event.type == Auction.EventType.BID_RETRACTION) {
	   this.div.find('#auction-ui-winning-bid').empty();
	   this.div.find('#bid-history .bid-history-row').remove();
	   for (var i=0; i<this.auction.bid_history.length; i++) {
	       this.enter_bid(this.auction.bid_history[i]);
	   }
    }
	
}

AuctionUI.prototype.enter_bid = function(bid) {
    this.div.find('#auction-ui-winning-bid').text(bid.owner.name + ' for $' + bid.amount);
    this.setMinBid(bid.amount+1);
    
    var bid_history_row = $('<tr class="bid-history-row"><td>' + 
			    bid.owner.name + '</td><td>$' + bid.amount + '</td></tr>');
    var bid_table = this.div.find('#bid-history');

    bid_table.find('tbody').append(bid_history_row);
    $('#bid-history-div').css({scrollTop: bid_table.prop('scrollHeight')});
}

AuctionUI.prototype.hide = function() {
    this.div.css('display', 'none');
}

AuctionUI.prototype.show = function() {
    this.div.css('display', '');
}

var BidUI = function (ui_div, owner, auction_ui) {
    this.div = ui_div;
    this.owner = owner;
    this.auction_ui = auction_ui;

    var self = this;
    this.div.find('button').text(owner.name).on('click', function() {
	   if (self.auction_ui.auction != null) {
	       var bid_amount = self.getBidAmount();
	       if (!isNaN(bid_amount)) {
		      self.auction_ui.auction.proposeBid(new Bid(self.owner, bid_amount));
	       }
	   }
    });
}

BidUI.prototype.getBidAmount = function () {
    return parseInt(this.div.find('input').val());
}

BidUI.prototype.setMinBid = function (min_bid) {
    if (min_bid > this.owner.maxBid()) {
	this.div.find('button').attr('disabled', 'disabled');
	this.div.find('input').val('').attr('disabled', 'disabled');
    } else {
	this.div.find('button').removeAttr('disabled');
	this.div.find('input').val(min_bid).removeAttr('disabled');
    }
}