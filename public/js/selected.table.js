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
		
		
		if (last_selected_row) {
			$( ".btn-action" ).each(function() {
				var btn_id = $(this).attr('id');
				$(this).attr('href', getPathById(btn_id, row_id));
				
				var data_can = last_selected_row.data('can-'+btn_id);
				var show = data_can === undefined || data_can=='1';
				
				if (show) {
					$(this).removeClass('disabled');
				} else {
					$(this).addClass('disabled');
				}
			});
		} else {
			$('.btn-action').addClass('disabled');
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