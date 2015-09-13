$(document).ready(function(){
	
	var last_selected_row;
	
	$('.order_row').click(function () {
		var order_id = $(this).attr('id');
		
		//подсветка клика
		if ($(this).hasClass('success')) {
			$(this).removeClass('success');
			last_selected_row = null;
		} else {
			if (last_selected_row) last_selected_row.removeClass('success');
			$(this).addClass('success');
			last_selected_row = $(this);
		}
		
		//вкл/отк клавиши действий
		if (last_selected_row) {
			$('.btn-action').removeClass('disabled');
		} else {
			$('.btn-action').addClass('disabled');
		}
		
		//выставим url
		if (last_selected_row) {
			$( ".btn-action" ).each(function() {
				var btn_id = $(this).attr('id');
				$(this).attr('href', params[btn_id+'_url'] + order_id)
			});
		}
	});
	
	$('.order_row').dblclick(function () {
		var order_id = $(this).attr('id');
		var url = params.view_url + order_id;
		window.location.href = url;
	});
	
});