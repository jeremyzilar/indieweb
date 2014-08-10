<div class="<?php echo GRID; ?>">
  <header class="entry-header">
  <?php indieweb_entry_head($post->ID); ?>
  </header><!-- .entry-header -->

  <div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php //include INC . 'mentions.php'; ?>
</div>