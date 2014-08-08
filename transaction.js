var Transaction = function(player, price, owner) {
    this.player = player;
    this.price = price;
    this.owner = owner;
}

var TransactionLog = function() {
    this.log = [];
    this.observers = [];
}

TransactionLog.prototype.push = function(transaction) {
    this.log.push(transaction);
    this.notifyObservers();
}

TransactionLog.prototype.last = function(transaction) {
    if (this.log.length == 0) {
        return null;
    }
    return this.log[this.log.length-1];
}

TransactionLog.prototype.pop = function() {
    if (this.log.length > 0) {
        var last = this.log.pop();
        this.notifyObservers();
        return last;
    } else {
        return null;
    }
}

TransactionLog.prototype.registerObserver = function(observer) {
    this.observers.push(observer);
}

TransactionLog.prototype.notifyObservers = function() {
    for(var i=0; i<this.observers.length; i++) {
        this.observers[i].transactionLogChange(this);
    }
}

var LastTransactionUI = function(div_id, transaction_log) {
    this.div = $('#'+div_id);
    this.transaction_log = transaction_log;
    this.transaction_log.registerObserver(this);
    
    var undo_btn = this.div.find('#undo-transaction');
    undo_btn.on('click', this, function(e) {
        var ui = e.data;
        if (ui.transaction_log.last() != null) {
            var t = ui.transaction_log.last();
            $.get("undo-transaction.php",
                {owner: t.owner.name,
                    player_name: t.player.name});
        }        
    });
}

LastTransactionUI.prototype.transactionLogChange = function (log) {
    if (log != this.transaction_log) {
       return;
   }

   var info_div = this.div.find('.jumbotron');

   info_div.empty();
   var last_transaction = this.transaction_log.last();

   if (last_transaction != null) {
       var player = last_transaction.player;

       info_div.text(player.name + " (" + player.position + ", " + player.team + ")" +
        " bought by " + last_transaction.owner.name + 
        " for $" + last_transaction.price + ".");
   }
}

LastTransactionUI.prototype.hide = function() {
    this.div.css('display', 'none');
}

LastTransactionUI.prototype.show = function() {
    this.div.css('display', '');
}
