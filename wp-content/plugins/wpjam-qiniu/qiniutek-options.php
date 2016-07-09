<?php
if(!function_exists('wpjam_option_page')){
	include(QINIUTEK_PLUGIN_DIR.'/include/wpjam-setting-api.php');
}

add_action( 'admin_menu', 'wpjam_qiniutek_admin_menu' );
function wpjam_qiniutek_admin_menu() {
	add_menu_page(						'七牛镜像存储',			'七牛镜像存储',	'manage_options',	'wpjam-qiniutek',		'wpjam_qiniutek_setting_page',	QINIUTEK_PLUGIN_URL.'/static/qiniutek-ico.png'	);
	add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储设置',		'基本设置',		'manage_options',	'wpjam-qiniutek',		'wpjam_qiniutek_setting_page'	);
	if( wpjam_qiniutek_get_setting('bucket') && wpjam_qiniutek_get_setting('access') && wpjam_qiniutek_get_setting('secret') ){
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 文件更新',			'文件更新',		'manage_options',	'wpjam-qiniutek-update','wpjam_qiniutek_update_page'	);
		add_submenu_page( 'wpjam-qiniutek',	'七牛镜像存储 &gt; 上传 Robots.txt','上传 Robots.txt',	'manage_options',	'wpjam-qiniutek-robots','wpjam_qiniutek_robots_page'	);
	}
}

add_action('admin_head','wpjam_qiniutek_admin_head');
function wpjam_qiniutek_admin_head(){
	global $plugin_page;
	if(in_array($plugin_page, array('wpjam-qiniutek', 'wpjam-qiniutek-update', 'wpjam-qiniutek-robots'))){ //只在相关的页面才加载 CSS 代码
?>
	<style>#icon-qiniutek{background-image: url("<?php echo QINIUTEK_PLUGIN_URL.'/static/qiniutek.png';?>");background-repeat: no-repeat;}</style>
<?php
	}
}

function wpjam_qiniutek_setting_page() {
	$labels =wpjam_qiniutek_get_option_labels();
	wpjam_option_page($labels, $title='七牛镜像存储设置', $type='default', $icon='qiniutek');
}


add_action( 'admin_init', 'wpjam_qiniutek_admin_init' );
function wpjam_qiniutek_admin_init() {
	wpjam_add_settings(wpjam_qiniutek_get_option_labels(),wpjam_qiniutek_get_default_option());
}


function wpjam_qiniutek_get_option_labels(){
	$option_group               =   'wpjam-qiniutek-group';
	$option_name = $option_page =   'wpjam-qiniutek';
	$field_validate				=	'wpjam_qiniutek_validate';


	$qiniutek_fileds = array(
		'host'	=> array('title'=>'七牛绑定的域名',	'type'=>'text',		'description'=>'设置为你在七牛绑定的域名即可。<strong>注意要域名前面要加上 http://</strong>。<br />如果博客安装的是在子目录下，比如 http://www.xxx.com/blog/，这里也需要带上子目录 /blog/ '),
		'bucket'=> array('title'=>'七牛空间名',		'type'=>'text',		'description'=>'设置为你在七牛绑定的空间名即可。'),
		
		'access'=> array('title'=>'ACCESS KEY',		'type'=>'text'),
		'secret'=> array('title'=>'SECRET KEY',		'type'=>'text'),
	);

	$local_fileds = array(		
		'exts'	=> array('title'=>'扩展名',			'type'=>'text',		'description'=>'设置要缓存静态文件的扩展名，请使用 | 分隔开，|前后都不要留空格。'),
		'dirs'	=> array('title'=>'目录',			'type'=>'text',		'description'=>'设置要缓存静态文件所在的目录，请使用 | 分隔开，|前后都不要留空格。'),
		'local'	=> array('title'=>'静态文件域名',		'type'=>'text',		'description'=>'如果图片等静态文件存储的域名和网站不同，可通过该字段设置。'),
		'jquery'=> array('title'=>'使用 jQuery 2.0',	'type'=>'checkbox',	'description'=>'jQuery 2.0 不再支持 IE 6/7/8，如果你的网站是面向移动或者不再向低端 IE 用户提供服务，可以勾选该选项。'),
	);

	$sections = array( 
    	'qiniutek-section'	=>array('title'=>'七牛设置',	'callback'=>'',	'fileds'=>$qiniutek_fileds),
    	'local-section'		=>array('title'=>'本地设置',	'callback'=>'',	'fileds'=>$local_fileds),
	);

	return compact('option_group','option_name','option_page','sections','field_validate');
}

function wpjam_qiniutek_get_default_option(){
 	return array(
		'host'		=> '',
		'bucket'	=> '',
		'access'	=> '',
		'secret'	=> '',
		'exts'		=> 'js|css|png|jpg|jpeg|gif|ico', 
		'dirs'		=> 'wp-content|wp-includes',
		'local'		=> home_url(),
		'jquery'	=> '0',
	);
}

function wpjam_qiniutek_validate( $wpjam_qiniutek ) {
	$current = get_option( 'wpjam-qiniutek' );

	if(empty($wpjam_qiniutek['minify'])){ //checkbox 未选，Post 的时候 $_POST 中是没有的，
		$wpjam_qiniutek['minify'] = 0;
	}

	if(empty($wpjam_qiniutek['jquery'])){ //checkbox 未选，Post 的时候 $_POST 中是没有的，
		$wpjam_qiniutek['jquery'] = 0;
	}

	return $wpjam_qiniutek;
}

function wpjam_qiniutek_get_setting($setting_name){
	$option = wpjam_qiniutek_get_option();
	return wpjam_get_setting($option, $setting_name);
}

function wpjam_qiniutek_get_option(){
	$defaults = wpjam_qiniutek_get_default_option();
	return wpjam_get_option('wpjam-qiniutek',$defaults);
}

/**
 * 更新缓存 
 **/

function wpjam_qiniutek_update_page(){
	global $plugin_page;

	$updates = '';
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		if ( !wp_verify_nonce($_POST[$plugin_page],'wpjam_qiniutek') ){
			ob_clean();
			wp_die('非法操作');
		}
		
		$updates = ($_POST['updates']);

		$updates_array = explode("\n", $updates);

		$msg = '';
		foreach ($updates_array as $update) {
			if(trim($update)){
				$msg = $msg.$update.wpjam_qiniutek_update_file($update);
			}
		}
	}

	?>
	<div class="wrap">
		<div id="icon-qiniutek" class="icon32"><br></div>
		<h2>更新文件</h2>

		<?php if(!empty($msg)){?>
		<div class="updated">
			<p><?php echo $msg;?></p>
		</div>
		<?php }?>

		<form method="post" action="<?php echo admin_url('admin.php?page='.$plugin_page); ?>" enctype="multipart/form-data" id="form">
		<table class="form-table" cellspacing="0">
			<tbody>
				<tr valign="top">
					<td>
						<p>请输入需要更新的文件，每行一个：</p>
						<textarea name="updates" rows="10" cols="50" id="updates" class="large-text code"><?php echo $updates; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('wpjam_qiniutek',$plugin_page); ?>
		<input type="hidden" name="action" value="edit" />
		<p class="submit"><input class="button-primary" type="submit" value="更新文件" /></p>
		</form>
		<ol>
			<li>点击“更新文件”按钮之后，只要文件后面显示更新成功，即代表更新成功。</li>
			<li>如果实时查看还是旧的文件，可能是你浏览器的缓存，你需要清理下缓存，或者等待自己更新。</li>
			<li>图片缩略图更新七牛是按照按照队列顺序进行的，需要等待一定的时间，只要看到原图更新即可。</li>
		</ol>
	</div>
<?php
}

/**
 * 提交 Robots 
 **/

function wpjam_qiniutek_robots_page(){
	global $plugin_page;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		if ( !wp_verify_nonce($_POST[$plugin_page],'wpjam_qiniutek') ){
			ob_clean();
			wp_die('非法操作');
		}
		
		$robots = ($_POST['robots']);

		if($robots){
			$msg = '';

			update_option('qiniutek_robots',$robots);

			wpjam_qiniutek_update_file('robots.txt'); // 如果有，先清理。
			$msg = wpjam_qiniutek_put('robots.txt', $robots); // 再上传

		}
	}

	$qiniutek_robots = get_option('qiniutek_robots');

	if(!$qiniutek_robots){
		$qiniutek_robots = '
User-agent: *
Disallow: /
User-agent: Googlebot-Image
Allow: /
User-agent: Baiduspider-image
Allow: /
		';
	}

	?>
	<div class="wrap">
		<div id="icon-qiniutek" class="icon32"><br></div>
		<h2>上传 Robots.txt</h2>

		<?php if(!empty($msg)){?>
		<div class="updated">
			<p><?php echo $msg;?></p>
		</div>
		<?php }?>

		<form method="post" action="<?php echo admin_url('admin.php?page='.$plugin_page); ?>" enctype="multipart/form-data" id="form">
		<table class="form-table" cellspacing="0">
			<tbody>
				<tr valign="top">
					<td>
						<p>上传 Robots.txt 文件，防止搜索引擎索引镜像的网页。</p>
						<textarea name="robots" rows="10" cols="50" id="robots" class="large-text code"><?php echo $qiniutek_robots; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field('wpjam_qiniutek',$plugin_page); ?>
		<input type="hidden" name="action" value="edit" />
		<p class="submit"><input class="button-primary" type="submit" value="更新Robots.txt" /></p>
		</form>
	</div>
<?php
}

function wpjam_qiniutek_update_file($file){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$file_array = parse_url($file);
	$key = str_replace('http://'.$file_array['host'].'/', '', $file);
	$err = Qiniu_RS_Delete($qiniutek_client, $qiniutek_bucket, $key);

	if ($err !== null) {
		$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
	} else {
		$msg = ' 清理成功<br />';
	}
	return $msg;
}

function wpjam_qiniutek_put_file($key, $file){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_PutFile($upToken, $key, $file);
	if ($err !== null) {
		$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
	} else {
		$msg = ' 上传成功<br />';
	}
	return $msg;
}

function wpjam_qiniutek_put($key, $str){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($qiniutek_bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_Put($upToken, $key, $str, null);

	if ($err !== null) {
		$msg = ' 发生错误：<span style="color:red">'.$err->Err.'</span><br />';
	} else {
		$msg = ' 上传成功<br />';
	}
	return $msg;
}

function wpjam_get_qiniutek_client(){

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	if(!class_exists('Qiniu_MacHttpClient')){
		require_once(WP_CONTENT_DIR."/plugins/wpjam-qiniu/sdk/rs.php");
	}	
	Qiniu_SetKeys($wpjam_qiniutek['access'], $wpjam_qiniutek['secret']);
	$qiniutek_client = new Qiniu_MacHttpClient(null);

	return $qiniutek_client;
}