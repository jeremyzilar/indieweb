<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
  <div class="container">
    <div class="row">
      <div class="<?php echo GRID; ?>">
			  <header class="entry-header">
			  	<?php indieweb_entry_head($post->ID); ?>
			  	<?php if ( is_single() ) : ?>
			  	<h1 class="entry-title"><?php the_title(); ?></h1>
			  	<?php else : ?>
			  	<h3 class="entry-title">
			  		<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			  	</h3>
			  	<?php endif; // is_single() ?>
			  </header><!-- .entry-header -->

				<?php if ( is_search() ) : // Only display Excerpts for Search ?>
					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div><!-- .entry-summary -->
				<?php else : ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php echo get_related(); ?>
					</div><!-- .entry-content -->
				<?php endif; ?>

			</div>
    </div> <!-- .row -->
  </div> <!-- .container -->
</article> <!-- #post -->