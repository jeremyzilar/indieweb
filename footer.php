  <section id="footer">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <?php $curYear = date('Y'); ?>
          <p class="datespan"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></a> / <?php echo $curYear; ?></p>
        </div>
      </div>
    </div>
  </section>

<?php wp_footer(); ?>

</body>
</html>
