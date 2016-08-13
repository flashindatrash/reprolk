$(document).ready(function(){
	
	//текстовое поле
	var input_response = $('#input_response');
	
	//url метода
	var path = params['method_path'];
	
	//выставим текстовое поле только для чтения
	input_response.prop('readonly', true);
	
	$('#execute').click(function () {
		var form = $(this).closest('form');
		if (form) {
			//сериализуем форму в объект
			var data = form.serializeArray();
			ajax(path, data, function (result) { 
				//распарсим JSON для чтения
				var response = JSON.stringify(result, null, '\t');
				//посчитаем сколько строк, чтобы расширить текстовое поле
				var rows = response.split(/\r\n|\r|\n/).length;
				
				input_response.val(response);
				input_response.attr('rows', rows);
			});
		}
	});
	
});