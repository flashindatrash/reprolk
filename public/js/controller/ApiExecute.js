$(document).ready(function(){
	
	var input_response = $('#input_response');
	
	input_response.prop('readonly', true);
	
	$('#execute').click(function () {
		var form = $(this).closest('form');
		if (form) {
			var data = form.serializeArray();
			ajax('/api/login', data, function (result) { 
				
				var response = JSON.stringify(result, null, '\t');
				var rows = response.split('\t').length;
				
				input_response.val(response);
				input_response.attr('rows', rows+1);
				//input_response.prop('height', input_response.prop('scrollHeight') + 'px');
			});
		}
	});
	
});