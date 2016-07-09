<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */

get_header();?>



<?php if(have_posts()) : ?>

 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>


<?php while(have_posts()) : the_post(); ?>
		
		<div class="post">
		<div class="posthead">
<div class="dater">
		
		<p class="day"><a href="<?php the_permalink(); ?>" ><?php the_time('j'); ?></a></p>
		<p class="monthyear"><a href="<?php the_permalink(); ?>" ><?php the_time('M y'); ?></a></p>
		</div>

			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			
			</div>
			
			
			<div class="entry">
			<?php the_post_thumbnail(array(120,120), array('class' => 'alignright')); ?>
				<?php the_content('Read on &raquo;'); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'cleanfrog' ), 'after' => '</div>' ) ); ?>
			</div>
			<p class="postmetadata">
			<span class="cat">Posted in <?php the_category(', ') ?></span> | 
			<span class="comm"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
			<?php edit_post_link('Edit', '&nbsp;|&nbsp;', ''); ?>
			<?php if ($sa_settings['cf_hidetags'] == '') { the_tags('<br /><span class="tags">Tags: ',', ', '</span>'); } ?>
			</p>
		</div>
<?php endwhile; ?>
		<div class="navigation">
		
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

<?php endif; ?></div>


<?php get_sidebar(); ?><?php get_footer(); ?>