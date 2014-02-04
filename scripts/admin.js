jQuery(function(){iworks_tabulator_init();});
/**
 * Tabulator Bootup
 */
function iworks_tabulator_init()
{
    if (!jQuery("#hasadmintabs").length) {
        return;
    }
    last_used_tab_name = jQuery('#hasadmintabs').data('prefix')+'last_used_tab';
    jQuery('#hasadmintabs').prepend("<ul><\/ul>");
    jQuery('#hasadmintabs > fieldset').each(function(i){
        id      = jQuery(this).attr('id');
        rel     = jQuery(this).attr('rel');
        caption = jQuery(this).find('h3').text();
        if ( rel ) {
            rel = ' class="'+rel+'"';
        } else {
            rel = '';
        }
        jQuery('#hasadmintabs > ul').append('<li><a href="#'+id+'"><span'+rel+'>'+caption+"<\/span><\/a><\/li>");
        jQuery(this).find('h3').hide();
    });
    index = 0;
    jQuery('#hasadmintabs h3').each(function(i){
        if ( jQuery(this).hasClass( 'selected' ) ) {
            index = i;
        }
    });
    if ( index < 0 ) index = 0;
    jQuery("#hasadmintabs").tabs({ active: index });
    jQuery('#hasadmintabs ul a').click(function(i){
        jQuery('#hasadmintabs input[name='+last_used_tab_name+']').val(jQuery(this).parent().index());
    });
    jQuery('#hasadmintabs h3').parent().parent().each(function(){
        if(jQuery(this).is('tr')){
            jQuery(this).hide();
        }
    });
}
