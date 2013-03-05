if ($("#wamFormWrapper").length > 0) {
	$("#wamFormWrapper").toggleClass('wamHide');
} else {
	$('body').prepend('<div id="wamFormWrapper"><iframe src="http://www.perdu.com" /></div>');	
}

