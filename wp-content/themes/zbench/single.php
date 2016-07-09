<link rel="stylesheet" href="http://files.cnblogs.com/xguo/prettify.css" />
<link rel="stylesheet" href="http://files.cnblogs.com/xguo/github.css"/>
<script src="http://blog.me115.com/wp-content/themes/zbench/jquery.js" type="text/javascript"></script> 
<script src="http://files.cnblogs.com/xguo/google-code-prettify.js"></script>

<?php get_header(); ?>
<div id="content">
	<?php the_post(); ?>
	<div <?php post_class('post-single'); ?> id="post-<?php the_ID(); ?>"><!-- post div -->
		<h2 class="title"><?php the_title(); ?></h2>
		<div class="post-info-top">
			<span class="post-info-date">
				<?php _e('Posted by', 'zbench'); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php printf( __( 'View all posts by %s', 'zbench' ), get_the_author() ) ?>" rel="author"><?php the_author(); ?></a>
				<?php _e('on', 'zbench'); ?>
				<?php the_time(get_option( 'date_format' )); ?>
				<?php edit_post_link(__('Edit','zbench'), '[', ']'); ?>
			</span>
			<?php if (comments_open()) : ?>
			<span class="addcomment"><a href="#respond"  rel="nofollow" title="<?php _e('Leave a comment ?', 'zbench'); ?>"><?php _e('Leave a comment', 'zbench'); ?></a><?php comments_number(' (0)', ' (1)', ' (%)'); ?></span>
			<span class="gotocomments"><a href="#comments"  rel="nofollow" title="<?php _e('Go to comments ?', 'zbench'); ?>"><?php _e('Go to comments', 'zbench'); ?></a></span>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
		<div class="entry">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page_link"><strong>' . __( 'Pages:', 'zbench' ) . '</strong>' , 'after' => '</div>' ) ); ?>
		</div><!-- END entry -->
		<div class="add-info">
			<?php if(function_exists('st_related_posts')) { st_related_posts('title=<h3>'._e('Related Posts','zbench').'</h3>'); } ?>
		</div>
		<div class="post-info-bottom">
			<span class="post-info-category"><?php the_category(', '); ?></span><span class="post-info-tags"><?php the_tags('', ', ', ''); ?></span>
		</div>
		<div id="nav-below">
			<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&larr;</span> %title' ); ?></div>
			<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&rarr;</span>' ); ?></div>
		</div><!-- #nav-below -->					
	</div><!-- END post -->
	<?php comments_template( '', true ); ?>
</div><!--content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>

<script  type="text/javascript">
$(function(){
    $("pre").addClass("prettyprint");
    $(".entry").addClass("markdown-body");
    prettyPrint();
    var dirbef = '<div id="navCategory" class="dir"><h3>内容目录</h3><ul>', dirend = '</ul><//div>', dircontent = '';
$('.entry h2').each( function(i,e){ dircontent += '<li><a href="#h2' + i + '">' + $(e).text() + "</a></li>"; $(this).attr('id', 'h2' + i);  } );$( dirbef + dircontent + dirend ).insertBefore( '.entry' );});
</script>

