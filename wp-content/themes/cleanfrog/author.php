<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */

get_header();?>



<?php

	if ( have_posts() )
		the_post();
?>

				

<?php
// If a user has filled out their description, show a bio on their entries.
if ( get_the_author_meta( 'description' ) ) : ?>
					<div id="entry-author-info">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( '', 72 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description" class="entry">
							<h3><?php printf( __( 'About %s', '' ), get_the_author() ); ?></h3>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description	-->
					</div><!-- #entry-author-info -->
					
					
<?php endif; ?>
<h2 class="page-title author"><?php printf( __( 'Archive for %s', '' ), "<span class='vcard'>" . get_the_author() . "</span>" ); ?></h2>
<?php while(have_posts()) : the_post(); ?>
		
		<div class="post">
		<div class="posthead">
		<div class="dater">
		
		<p class="day"><a href="<?php the_permalink(); ?>" ><?php the_time('j'); ?></a></p>
		<p class="monthyear"><a href="<?php the_permalink(); ?>" ><?php the_time('M y'); ?></a></p>
		</div>

			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
			
			
			<div class="entry">
			<?php the_post_thumbnail(array(120,120), array('class' => 'alignright')); ?>
				<?php the_excerpt(); ?>
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
</div>


<?php get_sidebar(); ?><?php get_footer(); ?>