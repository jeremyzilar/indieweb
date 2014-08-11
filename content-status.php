<div class="<?php echo GRID; ?>">
  <header class="entry-header">
  <?php indieweb_entry_head($post->ID); ?>
  </header><!-- .entry-header -->

  <div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<?php 
    if (is_user_logged_in()) {
      include INC . 'mentions.php';
    }
  ?>
</div>