<?php
function wpjam_has_post_thumbnail(){
	return wpjam_get_post_thumbnail()?true:false;
}

function wpjam_post_thumbnail($size='thumbnail', $crop=1){
	if($post_thumbnail = wpjam_get_post_thumbnail(null, $size, $crop)){
		echo $post_thumbnail;
	}
}

function wpjam_get_post_thumbnail($post=null, $size='thumbnail', $crop=1, $class="wp-post-image"){
	if(!$post){
		global $post;
	}

	$post_thumbnail_src = wpjam_get_post_thumbnail_src($post, $size, $crop);

	if($post_thumbnail_src){

		extract(wpjam_get_dimensions($size));

		$width_attr	= $width?' width="'.$width.'"':'';
		$height_attr = $height?' height="'.$height.'"':'';

		return '<img src="'.$post_thumbnail_src.'" alt="'.esc_attr($post->post_title).'" class="'.$class.'"'.$width_attr.$height_attr.' />';
	}else{
		return false;
	}	
}

function wpjam_get_post_thumbnail_src($post=null, $size='thumbnail', $crop=1){
	if(!$post){
		global $post;
	}

	$post_id = $post->ID;

	$post_thumbnail_uri = wp_cache_get($post_id,'post_thumbnail_uri');

	if($post_thumbnail_uri === false){
		
		$post_thumbnail_uri = wpjam_get_post_thumbnail_uri($post);		
		
		wp_cache_set($post_id, $post_thumbnail_uri, 'post_thumbnail_uri', 60);
	}

	if($post_thumbnail_uri){
		extract(wpjam_get_dimensions($size));
		$post_thumbnail_src = apply_filters('wpjam_thumbnail', $post_thumbnail_uri, $width, $height, $crop);
		
		return $post_thumbnail_src;
	}else{
		return false;
	}
}

//清理缓存
add_action('save_post','wpjam_clear_thumb_cache');
function wpjam_clear_thumb_cache($post_id){
	wp_cache_delete($post_id,'post_thumbnail_src');
}

function wpjam_get_post_thumbnail_uri($post=null){

	$post_thumbnail_uri = apply_filters('wpjam_post_thumbnail_uri',false, $post);

	if($post_thumbnail_uri){
		return $post_thumbnail_uri;		
	}

	if(!$post){
		global $post;
	}

	$post_id = $post->ID;

	if(has_post_thumbnail($post_id)){
		return  wp_get_attachment_url(get_post_meta($post_id,'_thumbnail_id',true));
	}

	$first_img = get_post_first_image(do_shortcode($post->post_content));
	if($first_img){
		return $first_img;
	}
}

//copy from image_constrain_size_for_editor
function wpjam_get_dimensions($size){
	global $content_width, $_wp_additional_image_sizes;

	$width = 0;
	$height = 0;

	if ( is_array($size) ) {
		$width = $size[0];
		$height = $size[1];
	}
	elseif ( $size == 'thumb' || $size == 'thumbnail' ) {
		$width = intval(get_option('thumbnail_size_w'));
		$height = intval(get_option('thumbnail_size_h'));

		// last chance thumbnail size defaults
		if ( !$width && !$height ) {
			$width = 128;
			$height = 96;
		}
	}
	elseif ( $size == 'medium' ) {
		$width = intval(get_option('medium_size_w'));
		$height = intval(get_option('medium_size_h'));
		// if no width is set, default to the theme content width if available
	}
	elseif ( $size == 'large' ) {
		// We're inserting a large size image into the editor. If it's a really
		// big image we'll scale it down to fit reasonably within the editor
		// itself, and within the theme's content width if it's known. The user
		// can resize it in the editor if they wish.
		$width = intval(get_option('large_size_w'));
		$height = intval(get_option('large_size_h'));
		if ( intval($content_width) > 0 )
			$width = min( intval($content_width), $width );
	} elseif ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) && in_array( $size, array_keys( $_wp_additional_image_sizes ) ) ) {
		$width = intval( $_wp_additional_image_sizes[$size]['width'] );
		$height = intval( $_wp_additional_image_sizes[$size]['height'] );
		if ( intval($content_width) > 0 && 'edit' == $context ) // Only in admin. Assume that theme authors know what they're doing.
			$width = min( intval($content_width), $width );
	}
	// $size == 'full' has no constraint
	else {
		//没了
	}

	return compact('width','height');
}

if(!function_exists('get_post_first_image')){

	function get_post_first_image($post_content){
		preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches);
		if($matches){	 
			return $matches[1][0];
		}else{
			return false;
		}
	}
}

add_filter('wpjam_thumbnail','wpjam_get_qiniu_thumbnail',10,4);
function wpjam_get_qiniu_thumbnail($img_url, $width=0, $height=0, $crop=1){
	if(defined('CDN_HOST')){
		$img_url = str_replace(LOCAL_HOST, CDN_HOST, $img_url);

		if($width && $height){
			if($crop){
				return add_query_arg( array('imageView/1/w/'.$width.'/h/'.$height => ''), $img_url );
			}else{
				return add_query_arg( array('imageView/2/w/'.$width.'/h/'.$height => ''), $img_url );
			}
		}elseif($width){
			return $img_url.'?imageView/2/w/'.$width;
		}elseif($height){
			return $img_url.'?imageView/2/h/'.$height;
		}
		return $img_url;
	}else{
		return $img_url;
	}
}

add_filter('manage_posts_columns', 'wpjam_manage_posts_columns_add_thumbnail');
add_filter('manage_pages_columns', 'wpjam_manage_posts_columns_add_thumbnail');
function wpjam_manage_posts_columns_add_thumbnail($columns){
    $columns['thumbnail'] = '缩略图';
    return $columns;
}

add_action('manage_posts_custom_column','wpjam_manage_posts_custom_column_show_thumbnail',10,2);
add_action('manage_pages_custom_column','wpjam_manage_posts_custom_column_show_thumbnail',10,2);
function wpjam_manage_posts_custom_column_show_thumbnail($column_name,$id){
    if ($column_name == 'thumbnail') {
        wpjam_post_thumbnail(array(100,80));
    }
}