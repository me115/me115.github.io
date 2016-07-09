<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */
 
 if (!isset($sa_settings)){
$sa_settings = get_option( 'sa_options' ); //gets the current value of all the settings as stored in the db
}
?>
<div id="clearer">
</div>
</div>
</div>
<div id="footer">
	<div id="footcontain">
	

	<ul class="footbar" id="footer-left"><li></li>
	<?php dynamic_sidebar(2); ?>
	</ul>
	<ul class="footbar" id="footer-center"><li></li>
	<?php dynamic_sidebar(3); ?>
	</ul>
	<ul class="footbar" id="footer-right"><li></li>
	<?php dynamic_sidebar(4); ?>
	</ul>
		
			<p class="hardfoot">Copyright &copy; <?php echo date('Y'); ?>. Cleanfrog Theme by
			<a href="http://nearfrog.com">NearFrog</a>. Powered 
			by <a href="http://wordpress.org">Wordpress</a></p>
		
	
	</div>
</div>
<?php if ($sa_settings['cf_analytics_code'] != '') { ?>
<?php echo(stripslashes ($sa_settings['cf_analytics_code']));?>
<?php } ?>

<?php wp_footer(); ?>
</body>

</html>
