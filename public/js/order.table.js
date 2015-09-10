$(document).ready(function(){

	$('.order_row').dblclick(function () {
		var order_id = $(this).attr('id');
		var url = params.view_url + order_id;
		window.location.href = url;
	});
	
});