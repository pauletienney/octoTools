
$(document).ready(function() {
	// Handle smooth scrolling
	// NB : requires scrollTo plugin (https://github.com/flesler/jquery.scrollTo)
    $(".smoothScroll").click(function(event){
        event.preventDefault();
        var targetName = $(this).attr('href');
        $('body').scrollTo($(targetName), 800);
    });

	// /////////////////////////
    // POPUP OPENER
    // /////////////////////////
    $('.popup').click(function(){
        var href = $(this).attr('href');
        return !window.open(href, '', 'width=500,height=500')
    });
});