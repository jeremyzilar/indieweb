<?php 

function writer(){
  if(is_user_logged_in() && function_exists('writer') && current_user_can('publish_posts')) {

    // global $current_user;
    $current_user = wp_get_current_user();

    $user_id = get_current_user_id();

    $requestUri = $_SERVER['REQUEST_URI'];

    // Post Parent — because I want to be able to post child posts.
    $post_parent = '';
    if (is_single()){
      global $post;
      $currentid = $_GET['post'];
      $post_parent = $post->ID;
    }

    $wp_nonce_field = wp_nonce_field( 'new-post' );

    echo <<< EOF
    <section id="writer">
      <div class="container">
        <div class="col-xs-12 box">
          <form name="new-post" method="post" action="">
            <input type="hidden" name="action_post" value="post" />

            <!-- WP Nonce -->
            $wp_nonce_field

            <!-- Post Status -->
            <input type="hidden" name="post_status" tabindex="1" value="publish"/>

            <!-- Post format -->
            <input type="hidden" name="post_format" tabindex="1" value="standard"/>
            
            <!-- Post Parent --> 
            <input type="hidden" name="post_parent" id="post_parent" tabindex="1" value="$post_parent"/>

            <!-- Headline -->
            <div class="form-group">
              <label for="writerHeadline">Headline</label>
              <input type="text" name="post_title" class="form-control" id="writerHeadline" placeholder="Headline">
            </div>

            <!-- Text Area -->
            <div class="form-group">
              <textarea name="postText" class="form-control" rows="3"></textarea>
            </div>

            <!-- Related Url -->
            <div class="form-group">
              <label for="relatedUrl">Related URL</label>
              <input type="text" id="relatedUrl" name="relatedUrl" placeholder="http://" class="form-control" tabindex="3" />
            </div>

            <input checked="checked" type="hidden" value="$requestUri" name="writerUrl" >

            <!-- Submit -->
            <input id="submit_post" class="submit btn btn-sm btn-primary" type="submit" value="Post it" />           
          </form>
        </div>
      </div><!-- .container -->
    </section><!-- section .space -->
EOF;
  }
}

// Add to header
function writerHeader() {
  global $writerVariables;
  if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_post']) && $_POST['action_post'] == 'post') {


      if (!is_user_logged_in()) {
        wp_redirect( get_bloginfo( 'url' ) . '/' );
        exit;
      }

      if( !current_user_can('publish_posts')) {
        wp_redirect( get_bloginfo( 'url' ) . '/' );
        exit;
      }

      check_admin_referer( 'new-post' );
      $user_id       = get_current_user_id();
      $post_content  = $_POST['postText'];
      $relatedUrl    = $_POST['relatedUrl'];
      $returnUrl     = $_POST['writerUrl'];
      $post_parent   = $_POST['post_parent'];
      $post_format   = $_POST['post_format'];
      $post_title    = strip_tags($_POST['post_title']);
      $post_status   = $_POST['post_status'];

      // if title was kept empty, trim content for title 
      // & add to asides category if it exists (unless another
      // category was explicitly chosen in form)
      // if (empty($post_title)) {
      //     $post_title      = strip_tags( $post_content );    
      //     $char_limit      = 40;    
      //     if( strlen( $post_title ) > $char_limit ) {
      //         $post_title = substr( $post_title, 0, $char_limit ) . ' ... ';
      //     }    
      //     // if "asides" category exists & title is empty, add to asides:
      //     if ($asidesCatID = get_cat_id($writerVariables['asidesCatName'])){
      //         $post_category = array($asidesCatID); 
      //     } 
      // } 

      // create the post
      $new_post_id = wp_insert_post( array(
        'post_author'   => $user_id,        
        'post_title'    => $post_title,
        // 'post_category' => $post_category,
        'post_content'  => $post_content,
        // 'tags_input'    => $tags,
        'post_status'   => $post_status,
        'post_parent'   => $post_parent,
        'post_format'   => $post_format,
        'relatedUrl'    => $relatedUrl
      ));
      add_post_meta($new_post_id, 'related_link_url', $relatedUrl, true);
      
      if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_post']) && $_POST['action_post'] == 'post') {
        set_post_format($new_post_id , 'standard');
      } else if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_link']) && $_POST['action_link'] == 'post' ) {
        set_post_format($new_post_id , 'link');
      }
      
      // if (is_single()) {
      //   // Update parent post
      //   $current_post = array();
      //   $current_post['ID'] = $post_parent;
      //   // Update the post into the database
      //   wp_update_post( $current_post );
      // }
      
      // now redirect back to blog
      if ($post_status == 'draft') { 
        $postresult = "?writerDraft=1";
      } else { 
        $postresult = ''; 
      }
      wp_redirect( $returnUrl . $postresult );
      exit;
  }
}

// pass wpurl from php to js
function writer_jsvars() {
  ?><script type='text/javascript'>
  // <![CDATA[
  var ajaxUrl = "<?php echo esc_js( get_bloginfo( 'wpurl' ) . '/wp-admin/admin-ajax.php' ); ?>";
  //]]>
  </script><?php
}

// Actions
add_action('get_header', 'writerHeader');
add_action('wp_head', 'writer_jsvars');

?>