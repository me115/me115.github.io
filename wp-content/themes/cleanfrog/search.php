<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */

get_header();?>

<?php if ( have_posts() ) : ?>
				<h2 class="page-title"><?php printf( __( 'Search Results for "%s"', 'cleanfrog' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				
				?><?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		
		<div class="post">
		<div class="posthead">
		<div class="dater">
		
		<p class="day"><a href="<?php the_permalink(); ?>" ><?php the_time('j'); ?></a></p>
		<p class="monthyear"><a href="<?php the_permalink(); ?>" ><?php the_time('M y'); ?></a></p>
		</div>

			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
			
			
			<div class="entry">
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

<?php endif; ?>

<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
						
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
<?php endif; ?></div>




<?php get_sidebar(); ?><?php get_footer(); ?>