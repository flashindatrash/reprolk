$(document).ready(function(){
	
	var preview_title = $("#preview_title");
	var preview_date = $("#preview_date");
	var preview_img = $("#preview_img");
	var revisions = $(".revision");
	var current = -1;
	
	$('#revision_prev').click(function (event) {
		event.preventDefault();
		removeLastActive();
		current--;
		if (current<0) current = revisions.length-1;
		$(revisions[current]).click();
	});
	
	$('#revision_next').click(function (event) {
		event.preventDefault();
		removeLastActive();
		current++
		if (current>revisions.length-1) current = 0;
		$(revisions[current]).click();
	});
	
	$('.revision').click(function (event) {
		event.preventDefault();
		removeLastActive();
		for (var i = 0; i<revisions.length; i++) {
			if ($(revisions[i]).attr('id')==$(this).attr('id')) {
				current = i;
				break;
			}
		}
		setActive();
		preview_img.attr("src", $(this).attr('href'));
		preview_title.html("Revision " + $(this).html());
		preview_date.html($(this).data("date"));
	});
	
	if (revisions.length>0) {
		$(revisions[0]).click();
	}
	
	function removeLastActive() {
		if (current!=-1) {
			$(revisions[current]).parent().removeClass("active");
		}
	}
	
	function setActive() {
		if (current!=-1) {
			$(revisions[current]).parent().addClass("active");
		}
	}
	
	function getPathByRevision(rid) {
		return params['revision_url'] + rid;
	}
	
});