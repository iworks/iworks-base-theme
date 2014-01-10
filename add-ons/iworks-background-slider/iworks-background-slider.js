var iworks_background_slider_index = 0;
var iworks_background_slider_timeout = 6000; // milisecounds
function iworks_background_slider_change_background() {
    $ = jQuery;
    iworks_background_slider_index++;
    if ( iworks_background_slider_index + 1 > imagearray.length ) {
        iworks_background_slider_index = 0;
    }
    iworks_background_slider_image_url = 'url('+imagearray[ iworks_background_slider_index ]+')';
    $('#header-1').css({ backgroundImage: iworks_background_slider_image_url  } ).animate( { opacity: 1 }, 'slow', function() {
        $('#header-2').animate( { opacity: 0 }, 'slow', function() {
            $(this).css({ backgroundImage: iworks_background_slider_image_url, opacity: 1 });
            $('#header-1').css({opacity:0});
        });
    });
    setTimeout( 'iworks_background_slider_change_background()', iworks_background_slider_timeout );
}

jQuery(document).ready(function($){
    var iworks_background_slider_container = $('#header');
    for( var i = 0; i < imagearray.length; i++ ) {
        a = new Image();
        a.src = imagearray[i];
    }
    iworks_background_slider_container.prepend(
        '<div id="header-1" class="header-background"></div>',
        '<div id="header-2" class="header-background"></div>'
        );
    setTimeout( 'iworks_background_slider_change_background()', iworks_background_slider_timeout );
});


