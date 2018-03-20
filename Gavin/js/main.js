jQuery(document).ready(function($){
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');

	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

});

/*Copied code from stack over flow*/

// Menu on scroll, sticky!
$(document).ready(function () {
            // grab the initial top offset of the navigation 
            var sticky_navigation_offset_top = $('.nav').offset().top;
            // our function that decides weather the navigation bar should have "fixed" css position or not.
            var sticky_navigation = function () {
                var scroll_top = $(window).scrollTop(); // our current vertical position from the top
                // if we've scrolled more than the navigation, change its position to fixed to stick to top, otherwise change it back to relative
                if (scroll_top > sticky_navigation_offset_top) {
                    $('.nav').css({
                        position: 'fixed'
                        , top: '0px'
                        ,backgroundImage: 'linear-gradient(-134deg, #3023AE 0%, #3F2AB2 10%, #C96DD8 100%)'
                        , zIndex: '99'
                        , transition: '0.5s'

                    });
                }
                else {
                    $('.nav').css({
                        position: 'fixed'
                        , top: '0px'
                        , background: 'transparent'
                        , borderBottom: 'none'
                    });
                }
            };
            // run our function on load
            sticky_navigation();
            // and run it again every time you scroll
            $(window).scroll(function () {
                sticky_navigation();
            });
    
});


/*Copied code from stack over flow*/

$(document).on('click', 'a', function(event){
    //event.preventDefault();

    $('html, body').animate({
        scrollTop: $( $.attr(this, 'href') ).offset().top
    }, 500);
});


