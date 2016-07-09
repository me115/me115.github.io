<?php get_header(); ?>
<div id="content">
	<?php the_post(); $nocomment_class=''; if ('open' != $post->comment_status) $nocomment_class=' post-page-nocomment'; ?>
	<div <?php post_class('post post-page'.$nocomment_class); ?> id="post-<?php the_ID(); ?>"><!-- post div -->
		<h2 class="title"><?php the_title(); ?></h2>
		<?php if ('open' == $post->comment_status) { ?>
		<div class="post-info-top">
			<span class="addcomment"><a href="#respond"  rel="nofollow" title="<?php _e('Leave a comment ?', 'zbench'); ?>"><?php _e('Leave a comment', 'zbench'); ?></a><?php comments_number(' (0)', ' (1)', ' (%)'); ?></span>
			<span class="gotocomments"><a href="#comments"  rel="nofollow" title="<?php _e('Go to comments ?', 'zbench'); ?>"><?php _e('Go to comments', 'zbench'); ?></a></span>
		</div>
		<?php } else { ?>
		<div class="post-info-top post-info-top-nocomment"></div>
		<?php } ?>
		<div class="clear"></div>
		<div class="entry">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page_link"><strong>' . __( 'Pages:', 'zbench' ) . '</strong>' , 'after' => '</div>' ) ); ?>
		</div><!-- END entry -->
	</div><!-- END post -->
	<?php comments_template( '', true ); ?>
</div><!--content-->
<script src="http://common.cnblogs.com/script/jquery.js" type="text/javascript"></script> 
<script  type="text/javascript">
$(function(){
    $("pre").addClass("prettyprint");
    $(".entry").addClass("markdown-body");
    var dirbef = '<div id="navCategory" class="dir"><h3>内容目录</h3><ul>', dirend = '</ul><//div>', dircontent = '';
$('.entry h2').each( function(i,e){ dircontent += '<li><a href="#h2' + i + '">' + $(e).text() + "</a></li>"; $(this).attr('id', 'h2' + i);  } );$( dirbef + dircontent + dirend ).insertBefore( '.entry' );});
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>