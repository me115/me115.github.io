<?php

/*
add_action('admin_menu', 'option_menu');

function option_menu() {
	add_options_page('Cleanfrog Options', 'Cleanfrog Options', 'manage_options', 'cleanfrog_options', 'cleanfrog_options');
}

function cleanfrog_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
include('options-page.php');
} 
*/

if (!isset($sa_settings)){
$sa_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db
}



//New options page
if (is_admin()){

$functions_path = TEMPLATEPATH . '/options/';
//Theme Options
require_once ($functions_path . 'options.php');
}

//END New options page



// Remove the links to the extra feeds such as category feeds if chosen
if(get_option('cf_cleanfeedurls') !='' ) {
remove_action( 'wp_head', 'feed_links_extra', 3 );
}

//This will activate the widgetised sidebar and footer
if ( function_exists('register_sidebar') )
    register_sidebar(array('name'=>'Sidebar'));
	
	$args1 = array('name' => 'Footer-left','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	$args2 = array('name' => 'Footer-center','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	$args3 = array('name' => 'Footer-right','before_title' => '<h4 class="widgettitle">','after_title' => "</h4>");
	register_sidebar($args1);
	register_sidebar($args2);
	register_sidebar($args3);
	
//Theme support for thumbnails and feed lnks	
add_theme_support( 'post-thumbnails' );
add_theme_support('automatic-feed-links'); 

//allows for a custom background
add_custom_background();
	
//Changes the excerpt "read more" link to a raquo	
	function new_excerpt_more($more) {
	global $post;
	return '...<a href="'. get_permalink($post->ID) . '">&raquo;</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

//Sets the customisable header menu
function register_my_menus() {
register_nav_menus(array('header-menu' => __( 'Header Menu' )));
}
add_action( 'init', 'register_my_menus' );

//Enques the custom scripts
if ( !is_admin() ) { // instruction to only load if it is not the admin area
 
	wp_enqueue_script('jquery');
   
   // cufon scripts
   wp_register_script('cufon',
       get_template_directory_uri() . '/cufon/cufon-yui.js',
       array('jquery'),
       '1.0' );
   wp_enqueue_script('cufon');
   
   wp_register_script('cufon-font',
       get_template_directory_uri() . '/cufon/Superclarendon_Rg_700.font.js',
       array('cufon'),
       '1.0' );
   wp_enqueue_script('cufon-font');
   
      wp_register_script('cufon-load',
       get_template_directory_uri() . '/cufon/cufon-load.js',
       array('cufon'),
       '1.0' );
   wp_enqueue_script('cufon-load');
   
   // Collapser script
   wp_register_script('collapser',
       get_template_directory_uri() . '/js/collapser.js',
       array('jquery'),
       '1.0' );
   // enqueue the script
   wp_enqueue_script('collapser');
}


//Loads an old comments.php file if wordpress does not support the new comment methods
add_filter( 'comments_template', 'legacy_comments' );
function legacy_comments( $file ) {
	if ( !function_exists('wp_list_comments') )
		$file = TEMPLATEPATH . '/old.comments.php';
	return $file;
}
//sets the content width global variable
$GLOBALS['content_width'] = 620;
if ( ! isset( $content_width ) ) {$content_width = 620;}




 
?>