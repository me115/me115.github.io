<?php
/*
Plugin Name: WPJAM 七牛镜像存储
Description: 使用七牛云存储实现 WordPress 博客静态文件 CDN 加速！
Plugin URI: http://blog.wpjam.com/project/wpjam-qiniutek/
Author: Denis
Author URI: http://blog.wpjam.com/
Version: 0.81
*/

define('QINIUTEK_PLUGIN_URL', plugins_url('', __FILE__));
define('QINIUTEK_PLUGIN_DIR', WP_PLUGIN_DIR.'/'. dirname(plugin_basename(__FILE__)));

include(QINIUTEK_PLUGIN_DIR.'/qiniutek-options.php');
if(!function_exists('wpjam_post_thumbnail')){
	include(QINIUTEK_PLUGIN_DIR.'/wpjam-thumbnail.php');
}
	//定义在七牛绑定的域名。
define('CDN_HOST',wpjam_qiniutek_get_setting('host'));
define('LOCAL_HOST',wpjam_qiniutek_get_setting('local'));

function wpjam_qiniutek_cdn_replace($html){
	$cdn_exts	= wpjam_qiniutek_get_setting('exts');
	$cdn_dirs	= str_replace('-','\-',wpjam_qiniutek_get_setting('dirs'));

	if($cdn_dirs){
		$regex	=  '/'.str_replace('/','\/',LOCAL_HOST).'\/(('.$cdn_dirs.')\/[^\s\?\\\'\"\;\>\<]{1,}.('.$cdn_exts.'))([\"\\\'\s\?]{1})/';
		return preg_replace($regex, CDN_HOST.'/$1$4', $html);
	}else{
		$regex	= '/'.str_replace('/','\/',LOCAL_HOST).'\/([^\s\?\\\'\"\;\>\<]{1,}.('.$cdn_exts.'))([\"\\\'\s\?]{1})/';
		return preg_replace($regex, CDN_HOST.'/$1$3', $html);
	}
}

if(!is_admin() && CDN_HOST){

	add_action("wp_loaded", 'wpjam_qiniutek_start_ob_cache');
	function wpjam_qiniutek_start_ob_cache(){
		ob_start('wpjam_qiniutek_cdn_replace');
	}

	/*下面的代码不需要了*/
	//add_action("wp_footer", 'wpjam_qiniutek_stop_ob_cache' ,9999);
	/*function wpjam_qiniutek_stop_ob_cache(){
		$html = ob_get_contents();
		ob_end_clean();
		$regex = wpjam_qiniutek_get_regex();
		echo preg_replace($regex, CDN_HOST.'/$1$4', wpjam_minify_html($html));
	}*/

	//add_filter('the_content_feed','wpjam_qiniutek_cdn_replace');
	//add_filter('the_excerpt_rss', 'wpjam_qiniutek_cdn_replace');
}

add_action( 'wp_enqueue_scripts', 'wpjam_qiniutek_enqueue_scripts', 1 );
function wpjam_qiniutek_enqueue_scripts() {

	if(wpjam_qiniutek_get_setting('jquery')){
		wp_deregister_script( 'jquery' );
	    wp_register_script( 'jquery', 'http://cdn.staticfile.org/jquery/2.0.3/jquery.min.js', array(), '2.0.3' );
	}else{
		wp_deregister_script( 'jquery-core' );
	    wp_register_script( 'jquery-core', 'http://cdn.staticfile.org/jquery/1.10.2/jquery.min.js', array(), '1.10.2' );

		wp_deregister_script( 'jquery-migrate' );
	    wp_register_script( 'jquery-migrate', 'http://cdn.staticfile.org/jquery-migrate/1.2.1/jquery-migrate.min.js', array(), '1.2.1' );
	}
}


