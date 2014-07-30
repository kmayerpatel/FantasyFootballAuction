
var BlockUI = function(div_id) {
    this.div = $('#'+div_id);
    this.controller = null;
   
    // Start Auction Button
    var start_btn = this.div.find('#blockStartAuction');
    start_btn.on('click', this, function(e) {
	var ui = e.data;
	var name = ui.getPlayerName().trim();
	var team = ui.getPlayerTeam();
	var pos = ui.getPlayerPosition();

	if (name.length > 0) {
	    ui.setPlayerNameErrorStatus(false);
	    if (ui.controller != null) {
		ui.controller.startAuction(name, team, pos);
	    }
	} else {
	    ui.setPlayerNameErrorStatus(true);
	}
    });
}

BlockUI.prototype.registerController = function(controller) {
    this.controller = controller;
}

BlockUI.prototype.getPlayerName = function() {
    return this.div.find('#blockPlayerName').val();
}

BlockUI.prototype.setPlayerName = function(name) {
    this.div.find('#blockPlayerName').val(name);
}

BlockUI.prototype.getPlayerTeam = function() {
    return this.div.find('#blockPlayerTeam').val();
}

BlockUI.prototype.setPlayerTeam = function(team) {
    this.div.find('#blockPlayerTeam').val(team);
}

BlockUI.prototype.getPlayerPosition = function() {
    return this.div.find('#blockPlayerPosition').val();
}

BlockUI.prototype.setPlayerPosition = function(pos) {
    this.div.find('#blockPlayerPosition').val(pos);
}

BlockUI.prototype.setPlayerNameErrorStatus = function(status) {
    if (status) {
	this.div.find('#blockPlayerName').parents('.form-group').addClass('has-error');
    } else {
	this.div.find('#blockPlayerName').parents('.form-group').removeClass('has-error');
    }
}

BlockUI.prototype.reset = function() {
    this.setPlayerNameErrorStatus(false);
    this.setPlayerName('');
    this.setPlayerTeam('ARI');
    this.setPlayerPosition('QB');
}

BlockUI.prototype.hide = function() {
    this.div.css('display', 'none');
}

BlockUI.prototype.show = function() {
    this.div.css('display', '');
}
