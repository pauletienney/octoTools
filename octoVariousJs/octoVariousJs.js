// Handle smooth scrolling
// NB : requires scrollTo plugin (https://github.com/flesler/jquery.scrollTo)
    $(".smoothScroll").click(function(event){
        event.preventDefault();
        var targetName = $(this).attr('href');
        $('body').scrollTo($(targetName), 800);
    });