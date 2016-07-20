function change_type() {
	var v = $('#input_type').val();
	var elem = $('#input_value').closest('.form-group');
	if (v=='select') {
		elem.show();
	} else {
		elem.hide();
	}
}

$(document).ready(function(){
	$('#input_type').change(change_type);
	change_type();
});