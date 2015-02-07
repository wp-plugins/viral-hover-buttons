<?php
/*
Plugin Name: Viral Hover Buttons
Description: Adding overlay social share buttons on videos and images, making sharing easier.
Plugin URI: http://www.smartynetwork.com?ref=plugins_backend
Author: Altin Elezaj
Version: 1.0
Author URI: http://www.smartynetwork.com/product?ref=plugins_backend
*/

### CONSTANTS ###

define('VIRAL_HOVER_BUTTONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('VIRAL_HOVER_BUTTONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('VIRAL_SHARE_BUTTONS_KEY', 'viral_share_buttons');

### OPTIONS DEFAULTS ###

global $viral_hover_buttons_defaults;
$viral_hover_buttons_defaults = array(
	'position'	=>	'topleft',
	'style'		=>	'list',
	'fb_on'		=>	true,
	'tw_on'		=>	true,
	'pin_on'	=>	true,
	'gp_on'		=>	true,
	'fb_text'	=>	'Facebook',
	'tw_text'	=>	'Twitter',
	'gp_text'	=>	'Google+',
	'pin_text'	=>	'Pin it'
);

global $viral_hover_buttons_opt;
$viral_hover_buttons_opt = (object)get_option( 'viral_hover_buttons_options' );


### INIT ###

add_action( 'init', 'viral_hover_buttons_init' );
function viral_hover_buttons_init(){
	if( is_admin() ){
		require_once( VIRAL_HOVER_BUTTONS_PLUGIN_PATH . 'viral-hover-buttons-options.php' );
	}
	
	wp_enqueue_style( 'viral_hover_buttons_style', VIRAL_HOVER_BUTTONS_PLUGIN_URL.'css/viral-hover-buttons-styles.css' );		
	wp_enqueue_script( 'viral_hover_buttons_script', VIRAL_HOVER_BUTTONS_PLUGIN_URL . 'js/script.js', array('jquery'), true );	
	
}

/*
** ACTIVATION HOOK
*/
function viral_hover_buttons_activate() {
	global $viral_hover_buttons_defaults;
	if(  !get_option( 'viral_hover_buttons_options' ) ){
		update_option( 'viral_hover_buttons_options', $viral_hover_buttons_defaults );
	}
}
register_activation_hook( __FILE__, 'viral_hover_buttons_activate' );

/*
** UNINSTALL HOOK
*/
function viral_hover_buttons_uninstall() {
	// nothing here
}
register_uninstall_hook( __FILE__, 'viral_hover_buttons_uninstall' );


#################################################
##    BUTTONS for YOUTUBE VIDEOS & PLAYLISTS    ##
#################################################

add_filter( 'the_content' , 'video_hover_buttons' );
function video_hover_buttons($content) {
	global $post, $viral_hover_buttons_opt;
	$share_link = urlencode(get_permalink());

	// match any iframes
	$pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
	preg_match_all($pattern, $content, $matches);

		
	$stringButtons = viral_hover_get_buttons('video');
		
    foreach ($matches[0] as $match) {
        // wrap matched iframe with div
        $wrappedframe = '<div class="viral_hover_buttons_wrapper viral_hover_buttons_wrapper_video">' . $match . $stringButtons . '</div>';

        //replace original iframe with new in content
        $content = str_replace($match, $wrappedframe, $content);
    }

    return $content;    
}


################################################
##               IMAGE BUTTONS                ##
################################################

add_filter( 'the_content' , 'image_hover_buttons' );
function image_hover_buttons( $content ) {
	global $post, $viral_hover_buttons_opt;
	
	// Regex to find all <img ... > tags
	$mh_url_regex = "/\<img [^>]*src=\"([^\"]+)\"[^>]*>/";

	// If we get any hits then put the code before and after the img tags
	if ( preg_match_all( $mh_url_regex , $content, $mh_matches ) ) {
		for ( $mh_count = 0; $mh_count < count( $mh_matches[0] ); $mh_count++ ) {
				// Old img tag
				$mh_old = $mh_matches[0][$mh_count];

				// Get the img URL, it's needed for the button code
				$mh_img_url = preg_replace( '/^.*src="/' , '' , $mh_old );
				$mh_img_url = preg_replace( '/".*$/' , '' , $mh_img_url );
			
			list($img_width, $img_height, $img_type, $img_attr) = getimagesize($mh_img_url);
			
			if( $img_width>100 && $img_height>100 ){			
				
				// Put together the pinterest code to place before the img tag
				$mh_pinterest_code = '<div class="viral_hover_buttons_wrapper viral_hover_buttons_wrapper_image" style="width:'.$img_width.'px;  height:'.$img_height.'px;">';

				// Replace before the img tag in the new string
				$mh_new = preg_replace( '/^/' , $mh_pinterest_code , $mh_old );
				// After the img tag
				
				$mh_new .= viral_hover_get_buttons('image', $mh_img_url);						

					
				$mh_new = preg_replace( '/$/' , '</div>' , $mh_new );

				// make the substitution
				$content = str_replace( $mh_old, $mh_new , $content );
			}
		}
	}
	return $content;
}


################################
######### GET BUTTONS ##########
################################

function viral_hover_get_buttons($caller, $media_url=''){
	global $post, $viral_hover_buttons_opt;
	$share_title	= urlencode(get_the_title());	
	$share_link		= urlencode(get_permalink());
	$share_media	= urlencode($media_url);
	$buttons_HTML	= '';
	
	$buttons_HTML .= '<ul class="viral_hover_buttons_overlay viral_hover_buttons_overlay_'.$caller.' vhb_style_'.$viral_hover_buttons_opt->style.'  vhb_position_'.$viral_hover_buttons_opt->position.'">';
	
	// style SELECT?
	if( $viral_hover_buttons_opt->style=='select' ){
		$buttons_HTML .= '<li class="vhb_select_title"><a href="#">Share <span class="vhb_arrow_select"></span></a><ul><li>';
	}
	
	if( $viral_hover_buttons_opt->fb_on ){
		// Add Facebook button		
		$buttons_HTML .= '<li class="vhb_share_item facebook"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='. $share_link .'" target="_blank">'.$viral_hover_buttons_opt->fb_text.'</a></li>';
	}
	
	if( $viral_hover_buttons_opt->tw_on ){
		// Add Twitter button			
		$buttons_HTML .= '<li class="vhb_share_item twitter"><a target="_blank" href="http://www.twitter.com/share?&text=Check+this+video&amp;url='. $share_link .'" target="_blank">'.$viral_hover_buttons_opt->tw_text.'</a></li>';
	}
	
	if( $viral_hover_buttons_opt->gp_on ){
		// Add Google Plus button
		$buttons_HTML .= '<li class="vhb_share_item gplus"><a target="_blank" href="https://plus.google.com/share?url='. $share_link .'">'.$viral_hover_buttons_opt->gp_text.'</a></li>';
	}
	
	if( $viral_hover_buttons_opt->pin_on ){
		// Add Pinterest buttons
		$buttons_HTML .= '<li class="vhb_share_item pinterest"><a target="_blank" href="https://pinterest.com/pin/create/button/?url='.$share_link.'&media='.$share_media.'&description='.$share_title.'">'.$viral_hover_buttons_opt->pin_text.'</a></li>';
	}
	
	
	// style SELECT?
	if( $viral_hover_buttons_opt->style=='select' ){
		$buttons_HTML .= '</li></ul>';
	}
	
	
	$buttons_HTML . '</ul>';
	
	return $buttons_HTML;
}

?>