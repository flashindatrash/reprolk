function redirect(url, timeout) {
	setTimeout(function() {
		window.location.href = url;
	}, timeout);
}

function switchPlugin(event, state) {
	var name = $(this).data('name');
	var path = $(this).data('path');
	ajax(path, {name: name, enabled: state}, null);
}

function ajax(path, data, success) {
	$.ajax({
		url: path,
		data: data,
		success: success,
		dataType: "json"
	});
}

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
	$('[data-toggle="popover"]').popover();
	$('[data-switch="enabled"]:checkbox').bootstrapSwitch();
	$('[data-type="plugin"]:checkbox').on('switchChange.bootstrapSwitch', switchPlugin);
	
	//по клику дизейблим кнопки сабмита
	$('button:submit:not([disabled])').click(function () {
		var btn = $(this);
		var form = btn.closest('form');
		if (form) {
			btn.attr('disabled', true);
			form.submit();
			form.find('a').addClass('disabled');
		}
	});
	
	//на все формы с файлами подписываемся на ошибки
	$('input[type="file"]').each(function( index ) {
		var input = $(this);
		var form = input.closest('form');
		var submit = form ? form.find('button:submit') : undefined;
		
		input.on('change', function(event) {
			if (submit) submit.removeAttr('disabled');
		});
		input.on('fileclear', function(event) {
			if (submit) submit.removeAttr('disabled');
		});
		
		input.on('fileerror', function(event, data, msg) {
			if (submit) submit.attr('disabled', true);
			
			console.log(data.id);
			console.log(data.index);
			console.log(data.file);
			console.log(data.reader);
			console.log(data.files);
			alert(msg);
		});
	});
});