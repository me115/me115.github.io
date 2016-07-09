<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */

get_header();?>

<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="posthead">
		<div class="dater">
		
		<p class="day"><a href="<?php the_permalink(); ?>" ><?php the_time('j'); ?></a></p>
		<p class="monthyear"><a href="<?php the_permalink(); ?>" ><?php the_time('M y'); ?></a></p>
		</div>

			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
			
			
			<div class="entry">
			<?php the_post_thumbnail(array(120,120), array('class' => 'alignright')); ?>

				<?php the_content('Read on &raquo;'); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', '' ), 'after' => '</div>' ) ); ?>
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
		
			<div class="alignleft older"><?php next_posts_link('Older Entries') ?></div>
			<div class="alignright newer"><?php previous_posts_link('Newer Entries') ?></div>
		</div>

<?php endif; ?></div>

<?php get_sidebar(); ?><?php get_footer(); ?>