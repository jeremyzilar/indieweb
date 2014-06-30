<div class="<?php echo GRID; ?>">
  <header class="entry-header">
  	<h3 class="entry-title">
  		<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
  	</h3>
  </header><!-- .entry-header -->

  <div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
    <?php indieweb_entry_meta($post->ID); ?>
	</footer><!-- .entry-meta -->
	
	<?php include INC . 'mentions.php'; ?>
</div>