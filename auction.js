var Bid = function(owner, amount) {
    this.owner = owner;
    this.amount = amount;
}

Bid.prototype.isLegal = function() {
    var res = (this.amount <= this.owner.maxBid());
    return res;
}

var Auction = function(player, timestamp) {
    this.player = player;
    this.bid_history = [];
    this.status = Auction.Status.NOT_STARTED;
    this.observers = [];
    this.updateTimerID = null;
    this.last_bid_time = null;
    this.timestamp = timestamp;
}

Auction.Status = {};
Auction.Status.NOT_STARTED = 1;
Auction.Status.UNDERWAY = 2;
Auction.Status.GOING_ONCE = 3;
Auction.Status.GOING_TWICE = 4;
Auction.Status.SOLD = 5;
Auction.Status.CANCELLED = -1;

Auction.EventType = {};
Auction.EventType.STATUS_CHANGE = 1;
Auction.EventType.BID = 2;
Auction.EventType.BID_RETRACTION = 3;

Auction.TIMER_INTERVAL = 200;
Auction.GOING_ONCE_DELAY = 5000;
Auction.GOING_TWICE_DELAY = 7000;
Auction.SOLD_DELAY = 9000;

Auction.prototype.highBid = function() {
    if (this.bid_history.length == 0) return null;
    return this.bid_history[this.bid_history.length-1];
}

Auction.prototype.setStatus = function(status) {
    if (this.status != status) {
        this.status = status;
        this.notifyObservers(new AuctionEvent(Auction.EventType.STATUS_CHANGE, null));
    }

    if (this.status == Auction.Status.UNDERWAY ||
     this.status == Auction.Status.GOING_ONCE ||
     this.status == Auction.Status.GOING_TWICE) {
        /*
     if (this.updateTimerID == null) {
         var self = this;
         this.updateTimerID = setTimeout(function () {self.updateTimer()}, Auction.TIMER_INTERVAL);
     }
     */
 }
}

Auction.prototype.proposeRetraction = function() {
    if (this.bid_history.length == 0) {
     return;
 }
 var retracted_bid = this.bid_history[this.bid_history.length-1];

 $.get('bid.php', 
    {cancel: 1,
        bidder: retracted_bid.owner.name,
        bid: retracted_bid.bid,
        timestamp: this.timestamp});
}

Auction.prototype.confirmRetraction = function(bidder, bid, timestamp) {
    if ((timestamp != this.timestamp) || (this.bid_history.length == 0)) {
        return;
    }

    var last_bid = this.bid_history[this.bid_history.length-1];

    if ((bidder != last_bid.owner.name) || (bid != last_bid.bid)) {
        return;
    }

    this.bid_history.pop();
    this.notifyObservers(new AuctionEvent(Auction.EventType.BID_RETRACTION, null));

    if (this.bid_history.length > 0) {
        var rebid = this.bid_history.pop();
        this.confirmBid(rebid.owner, rebid.bid, this.timestamp);
    }
}

Auction.prototype.proposeBid = function(bid) {
    if (!bid.isLegal() ||
     this.status == Auction.Status.SOLD ||
     this.status == Auction.Status.CANCELLED) {
     return;
    }

    var cur_high_bid = this.highBid();
    if (cur_high_bid != null &&
	   bid.amount <= cur_high_bid.amount) {
	   return;
    }

    $.get("bid.php", {
        timestamp: this.timestamp,
        bidder: bid.owner.name,
        bid: bid.amount});
}

Auction.prototype.confirmBid = function(bidder, amount, timestamp) {
    if (timestamp != this.timestamp) {
        return;
    }

    this.setStatus(Auction.Status.UNDERWAY);

    var bid = new Bid(bidder, amount);
    this.bid_history.push(bid);
    this.last_bid_time = new Date();
    this.notifyObservers(new AuctionEvent(Auction.EventType.BID, bid));
}

Auction.prototype.updateTimer = function() {
    this.updateTimerID = null;
    var tdelta = this.timeSinceLastBid();

    if ((this.status == Auction.Status.UNDERWAY) &&
     (tdelta > Auction.GOING_ONCE_DELAY)) {
        $.get("going-once.php", {timestamp: this.timestamp});
    } else if ((this.status == Auction.Status.GOING_ONCE) &&
        (tdelta > Auction.GOING_TWICE_DELAY)) {
        $.get("going-twice.php", {timestamp: this.timestamp});
    } else if ((this.status == Auction.Status.GOING_TWICE) &&
        (tdelta > Auction.SOLD_DELAY)) {
        $.get("sold.php", {timestamp: this.timestamp});
    } 

    /* Following necessary to schedule update timer. */
    this.setStatus(this.status);
}

Auction.prototype.timeSinceLastBid = function() {
    if (this.last_bid_time == null) {
        return NaN;
    }

    var now = new Date();

    return (now.getTime() - this.last_bid_time.getTime());
}

Auction.prototype.registerObserver = function(observer) {
    this.observers.push(observer);
}

Auction.prototype.unregisterObserver = function(observer) {
    var updated_observers = [];
    for (var i=0; i<this.observers.length; i++) {
        if (this.observers[i] != observer) {
            updated_observers.push(this.observers[i]);
        }
    }
    this.observers = updated_observers;
}

Auction.prototype.notifyObservers = function(auction_event) {
    for (var i=0; i<this.observers.length; i++) {
        this.observers[i].auctionUpdate(this, auction_event);
    }
}

var AuctionEvent = function(type, data) {
    this.type = type;
    this.data = data;
}
