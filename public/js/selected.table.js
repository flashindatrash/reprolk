$(document).ready(function(){
	
	var last_selected_row;
	
	$('.table_row').click(function () {
		var row_id = $(this).attr('id');
		
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
				$(this).attr('href', getPathById(btn_id, row_id))
			});
		}
	});
	
	$('.table_row').dblclick(function () {
		var row_id = $(this).attr('id');
		var rehref = getPathById('view', row_id);
		if (rehref===false) return;
		window.location.href = rehref;
	});
	
	function getPathById(btn_id, id) {
		var url = params[btn_id+'_url'];
		if (!url) return false;
		return url + '?id=' + id;
	}
	
});