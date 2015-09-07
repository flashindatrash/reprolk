function redirect(url, timeout) {
	setTimeout(function() {
		window.location.href = url;
	}, timeout);
}

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});