<article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
  <div class="container">
    <div class="row">
      <div class="<?php echo GRID; ?>">
        <header class="entry-header">
        <?php indieweb_entry_head($post->ID); ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
          <?php the_content(); ?>
        </div><!-- .entry-content -->
        <div class="entry-summary hidden">
          <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <?php 
          include INC . 'mentions.php';
          if (is_user_logged_in()) {
            // include INC . 'mentions.php';
          }
        ?>
      </div>
    </div> <!-- .row -->
  </div> <!-- .container -->
</article> <!-- #post -->