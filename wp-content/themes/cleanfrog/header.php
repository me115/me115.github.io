<?php
/**
 * @package WordPress
 * @subpackage Cleanfrog
 */
 
 if (!isset($sa_settings)){
$sa_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db
}
global $sa_settings;
?>
<!DOCTYPE html>


<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head  profile="http://gmpg.org/xfn/11">

<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
<meta content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" http-equiv="Content-Type" />

<link href="<?php bloginfo('stylesheet_url'); ?>" media="screen" rel="stylesheet" type="text/css" />
<?php
if ($sa_settings['cf_custom_css'] != ''){
?><!-- Here is the custom css -->
<style type="text/css" media="screen"> 
<?php echo stripcslashes($sa_settings['cf_custom_css']); ?>
</style>
<?php } ?>

<?php if (is_numeric($sa_settings['cf_headerfontsize'])){?> 
<style type="text/css">
#header h1, #header h1 a{
	font-size: <?php echo($sa_settings['cf_headerfontsize']); ?>px;
}
</style>
<?php } ?>
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	
<?php //wp_get_archives('type=monthly&format=link'); ?><?php //comments_popup_script(); // off by default ?>
<?php if ($sa_settings['cf_header_code'] != ''){ echo stripslashes($sa_settings['cf_header_code']); }?>
<?php wp_head(); ?>
</head>
<?php flush(); ?>
<body <?php body_class(); ?>>

<div id="container">
	<div id="header">
	<?php if ($sa_settings['cf_bannerimage'] == '') { ?>
	
		<h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
		<h2><?php bloginfo('description'); ?></h2>	
<?php } else { ?>
		<a href="<?php echo home_url(); ?>">
		<img src="<?php echo $sa_settings['cf_bannerimage']; ?>" alt="<?php bloginfo('name'); ?>" style="margin-left:-10px; max-width:890px; height:auto;"/>
		</a>	
<?php } ?>
	</div>
	 <div id="navbox">
			<?php			
			$margs = array(
			'menu_id' => 'navbar',
			'container_class' => 'menu',
			'link_after' => '<span class="clearstick">&nbsp;/&nbsp;</span>',
			'depth' => '2'
			);			
			wp_nav_menu($margs);			
			?>
		
			<?php get_search_form(); ?>
		
<div id="navclearer"></div>
	</div>
	<div id="contentcontainer">
	<div id="content">

