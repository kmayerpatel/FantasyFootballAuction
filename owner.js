var Owner = function(name) {
    this.name = name;
    this.roster = [];
    this.rosterObservers = [];
}

Owner.MAX_ROSTER_SIZE = 14;
Owner.MIN_ROSTER_SIZE = 12;
Owner.SALARY_CAP = 100;

Owner.lookup = function(name) {
    for (var i=0; i<Owner.owners; i++) {
        if (Owner.owners[i].name == name) {
            return Owner.owners[i];
        }
    }
    return null;
}

Owner.prototype.addToRoster = function(transaction) {
    this.roster.push(transaction);
    this.notifyObservers();
}

Owner.prototype.maxBid = function() {
    if (this.roster.length >= Owner.MAX_ROSTER_SIZE) {
        return 0;
    }

    var balance = Owner.SALARY_CAP;

    for (var i=0; i<this.roster.length; i++) {
	   balance -= this.roster[i].price;
    }

    if (Owner.MIN_ROSTER_SIZE > this.roster.length+1) {
	   return balance - (Owner.MIN_ROSTER_SIZE - this.roster.length - 1);
    } else 
	   return balance;
    }
}

Owner.prototype.removeFromRoster = function(transaction) {
    var new_roster = [];
    for (var i = 0; i<this.roster.length; i++) {
	if (this.roster[i] != transaction) {
	    new_roster.push(this.roster[i]);
	}
    }
    this.roster = new_roster;
    this.notifyObservers();
}

Owner.prototype.registerRosterObserver = function(observer) {
    this.rosterObservers.push(observer);
}

Owner.prototype.notifyObservers = function() {
    for (var i=0; i<this.rosterObservers.length; i++) {
	   this.rosterObservers[i].rosterChange(this);
    }
}