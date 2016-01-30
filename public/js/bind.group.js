const PLUS = 'plus';
const MINUS = 'minus';

var all;
var current;
var form;
var input_ids;
var list_all;
var list_current;

$(document).ready(function(){
	all = params['all'];
	current = params['current'];
	form = $('#form-bind');
	input_ids = form.find('input[name=ids]');
	list_all = form.find('#list-all');
	list_current = form.find('#list-current');
	
	create();
});

function createItem(id, name, type) {
	return '<li class="list-group-item" id="' + type + id + '">' + name + '<div class="pull-right"><a href="javascript:' + type + '(' + id + ');"><span class="glyphicon glyphicon-' + type + '" aria-hidden="true"></span></a></div></li>';
}

function hasInActive(id) {
	return current.indexOf(id)!=-1;
}

function getIdByName(type, name) {
	return name.substr(type.length);
}

function update() {
	list_all.find('li').each(function() {
		alert($(this).attr('id'));
		var id = getIdByName(PLUS, $(this).attr('id'));
		if (hasInActive(id)) {
			console.log(id);
			$(this).hide();
		} else {
			$(this).show();
		}
	});
	var html = '';
	for (var i = 0; i<current.length; i++) { 
		html += createItem(current[i], all[current[i]], MINUS);
	}
	list_current.html(html);
	input_ids.val(current.join(','));
}

function create() {
	form.hide();
	var html = '';
	for (var id in all) { 
		html += createItem(id, all[id], PLUS);
	}
	list_all.html(html);
	update();
	form.show("slow");
}

function plus(id) {
	id = id.toString();
	if (!hasInActive(id)) {
		current.push(id);
		update();
	}
	return false;
}

function minus(id) {
	id = id.toString();
	if (hasInActive(id)) {
		current.splice(current.indexOf(id), 1);
		update();
	}
	return false;
}