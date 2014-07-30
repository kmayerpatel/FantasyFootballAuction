
var RosterUI = function(owner, container_id) {
    
    this.owner = owner;
    this.div = $('<div class="col-md-1 roster"></div>');

    var owner_info_div = $('<div class="row">'+
			   '<div class="col-md-offset-1 col-md-6 h4">' + owner.name + '</div>' +
			   '<div class="col-md-5">Max Bid: $<span class="roster-max-bid"></span></div>');

    this.div.append(owner_info_div);

    var roster_table = $('<table class="table table-striped table-condensed"></table>');
    roster_table.append($('<tr><th></th><th>Pos</th><th>Name</th><th>Team</th><th>Bye</th><th>Price</th></tr>'));
    for (var i=0; i<Owner.MAX_ROSTER_SIZE; i++) {
	roster_table.append($('<tr class="roster-slot"><th>'+(i+1)+'</th>'+
			      '<td class="roster-slot-position"></td>'+
			      '<td class="roster-slot-name"></td>'+
			      '<td class="roster-slot-team"></td>'+
			      '<td class="roster-slot-bye"></td>'+
			      '<td class="roster-slot-price"></td></tr>'));
    }
    
    var table_div = $('<div class="row"></div>');
    var inner_table_div = $('<div class="col-md-12"></div>');

    inner_table_div.append(roster_table);
    table_div.append(inner_table_div);
    this.div.append(table_div);

    $('#'+container_id).append(this.div);

    owner.registerRosterObserver(this);
    this.rebuild_roster();
}

RosterUI.prototype.rebuild_roster = function () {
    this.div.find('.roster-max-bid').text(this.owner.maxBid());

    var roster_slots = this.div.find('.roster-slot');
    for (var i=0; i<this.owner.roster.length; i++) {
	var info = this.owner.roster[i];
	var slot = $(roster_slots[i]);

	slot.find('.roster-slot-position').text(info.player.position);
	slot.find('.roster-slot-name').text(info.player.name);
	slot.find('.roster-slot-team').text(info.player.team);
	slot.find('.roster-slot-bye').text(byeWeeks[info.player.team]);
	slot.find('.roster-slot-price').text('$'+info.price);
    }
    for (var i=this.owner.roster.length; i<roster_slots.length; i++) {
	slot = $(roster_slots[i]);
	slot.find('.roster-slot-position').empty();
	slot.find('.roster-slot-name').empty();
	slot.find('.roster-slot-team').empty();
	slot.find('.roster-slot-bye').empty();
	slot.find('.roster-slot-price').empty();
    }
}

RosterUI.prototype.rosterChange = function(owner) {
    if (owner != this.owner) return;

    this.rebuild_roster();
}