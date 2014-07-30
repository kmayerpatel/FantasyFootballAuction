var Bid = function(owner, amount) {
    this.owner = owner;
    this.amount = amount;
}

Bid.prototype.isLegal = function() {
    var res = (this.amount <= this.owner.maxBid());
    return res;
}

var Auction = function(player) {
    this.player = player;
    this.bid_history = [];
    this.status = Auction.Status.NOT_STARTED;
    this.observers = [];
    this.updateTimerID = null;
    this.last_bid_time = null;
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
	if (this.updateTimerID == null) {
	    var self = this;
	    this.updateTimerID = setTimeout(function () {self.updateTimer()}, Auction.TIMER_INTERVAL);
	}
    }
}

Auction.prototype.retract = function() {
    if (this.bid_history.length == 0) {
	return;
    }
    var retracted_bid = this.bid_history.pop();

    var rebid = null;
    if (this.bid_history.length > 0) {
	rebid = this.bid_history.pop();
    }

    this.notifyObservers(new AuctionEvent(Auction.EventType.BID_RETRACTION, null));

    if (rebid != null) {
	this.bid(rebid);
    }
}

Auction.prototype.bid = function(bid) {
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
    
    this.setStatus(Auction.Status.UNDERWAY);

    this.bid_history.push(bid);
    this.last_bid_time = new Date();
    this.notifyObservers(new AuctionEvent(Auction.EventType.BID, bid));
}

Auction.prototype.updateTimer = function() {
    this.updateTimerID = null;
    var tdelta = this.timeSinceLastBid();

    if ((this.status == Auction.Status.UNDERWAY) &&
	(tdelta > Auction.GOING_ONCE_DELAY)) {
	this.setStatus(Auction.Status.GOING_ONCE);
    } else if ((this.status == Auction.Status.GOING_ONCE) &&
	       (tdelta > Auction.GOING_TWICE_DELAY)) {
	this.setStatus(Auction.Status.GOING_TWICE);
    } else if ((this.status == Auction.Status.GOING_TWICE) &&
	       (tdelta > Auction.SOLD_DELAY)) {
	this.setStatus(Auction.Status.SOLD);
    } else {
	this.setStatus(this.status);
    }
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
