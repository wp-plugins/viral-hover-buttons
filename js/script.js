jQuery(document).ready( function($){
	$('.viral_hover_buttons_plugin_checkbox').click( function(){	
		if ( $(this).attr('data-target') ) {
			var get_target = $(this).attr('data-target');
		
			$( ''+get_target ).toggleClass( "text_input_is_on" );
        }
	});
});