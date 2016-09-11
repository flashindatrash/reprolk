$(document).ready(function(){

	var input_type = $('#input_type');
	var input_value = $('#input_value');
	var input_customized = $('#input_customized');
	var input_mandatory = $('#input_mandatory');
	
	var group_value = input_value.closest('.form-group');
	var group_customized = input_customized.closest('.form-group');
	var group_mandatory = input_mandatory.closest('.form-group');
	
	function change_type() {
		var type = input_type.val();
		var customized = input_customized.prop('checked');
		
		if (type=='select' || type=='multiple') {
			if (!customized) {
				group_value.show();
			} else {
				group_value.hide();
				input_value.val("");
			}
			group_customized.show();
		} else {
			group_value.hide();
			group_customized.hide();
		}

		if (type=='checkbox') {
			group_mandatory.hide();
			input_mandatory.removeProp('checked');
		} else {
			group_mandatory.show();
		}
		
	}

	input_type.change(change_type);
	input_customized.change(change_type);
	change_type();
});