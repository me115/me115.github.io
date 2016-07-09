<?php

/**
 * @package WordPress
 * @subpackage Cleanfrog
 */

get_header();?>



<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
		
	<div class="post">
		<div class="posthead">
		<div class="dater"><p class="day"><?php the_time('j'); ?></p>
		<p class="monthyear"><?php the_time('M y'); ?></p></div>

		<h2><?php the_title(); ?></h2>
		<p class="postauthor">By <?php the_author_posts_link(); ?></p>			
		</div>
			
			<div class="entry">
				<?php if ($sa_settings['cf_hidepostthumb'] == '') {the_post_thumbnail('medium', array('class'=>'alignright'));} ?>
				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:'), 'after' => '</div>' ) ); ?>
			</div>
			<p class="postmetadata">
			<span class="cat">Posted in <?php the_category(', ') ?></span> | 
			<span class="comm"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></span>
			<?php edit_post_link('Edit', '&nbsp;|&nbsp;', ''); ?>
			<?php if ($sa_settings['cf_hidetags'] == '') { the_tags('<br /><span class="tags">Tags: ',', ', '</span>'); } ?>
			</p>
		
	</div>
		<?php comments_template(); ?>

<?php endwhile; ?>

<?php endif; ?></div>


<?php get_sidebar(); ?><?php get_footer(); ?>