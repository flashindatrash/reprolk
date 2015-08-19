function redirect(url, timeout) {
	setTimeout(function() {
		window.location.href = url;
	}, timeout);
}