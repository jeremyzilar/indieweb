<?php
// o o o o o o o o o o o o o o o o o o o o o o o o o o o 

/*
 * Copyright 2009 Jon Smajda (email: jon@smajda.com)
 *
 * This plugin reuses code from the Prologue and P2 Themes,
 * Copyright Joseph Scott, Matt Thomas, Noel Jackson, and Automattic
 * according to the terms of the GNU General Public License.
 * http://wordpress.org/extend/themes/prologue/
 * http://wordpress.org/extend/themes/p2/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * http://www.gnu.org/licenses/gpl.html
 * */


// Which category to use
function writerCatCheck() {
    if (is_home()) {
      $homefeed = get_option('writer_homefeed');
      return $homefeed;
    } else if (is_category()) {
      return get_cat_ID(single_cat_title('', false));
    } else {
      return get_option('default_category', 1);
    } 
  }

// Which tag to use
function writerTagCheck() {
  if (is_tag()) {
      $taxarray = get_term_by('name', single_tag_title('', false), 'post_tag', ARRAY_A);
      return $taxarray['name'];
  } else {
      return NULL;
  }
}

function writerLinkCheck() {
  if (get_post_meta($post->ID, 'url', true)) {
    $link = get_post_meta($post->ID, 'url', true);
    if (get_post_meta($post->ID, 'url', true) && $link == 'http://' ) {
      echo 'equal link';
    } else{
      echo 'not equal link';
    }
  }
}

function link_page_title($url) {
  $page = @file_get_contents($url);
  if (!$page) return null;
  $matches = array();
  if (preg_match('/<title>(.*?)<\/title>/', $page, $matches)) {
    return $matches[1];
  }
  else {
    return null;
  }
}



// Add to header
function writerHeader() {
    global $writerVariables;
    if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_post']) && $_POST['action_post'] == 'post' || 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_link']) && $_POST['action_link'] == 'post' ) {

        if (!is_user_logged_in()) {
            wp_redirect( get_bloginfo( 'url' ) . '/' );
            exit;
        }

        if( !current_user_can('publish_posts')) {
            wp_redirect( get_bloginfo( 'url' ) . '/' );
            exit;
        }

        check_admin_referer( 'new-post' );

        $user_id       = $current_user->user_id;
        $post_content  = $_POST['postText'];
        $tags          = $_POST['tags'];
        $post_URL    = $_POST['post_URL'];
		    $post_category  = array($_POST['catsdd']);
        $returnUrl     = $_POST['writerUrl'];
        $post_parent     = $_POST['post_parent'];
        $post_format     = $_POST['post_format'];
        $post_title    = strip_tags($_POST['post_title']);
        
        

        // set post_status 
        if ($_POST['postStatus'] == 'draft') {
            $post_status = 'draft';
        } else {
            $post_status = 'publish';    
        }

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
            'post_category' => $post_category,
            'post_content'  => $post_content,
            'tags_input'    => $tags,
            'post_status'   => $post_status,
            'post_parent'   => $post_parent,
            'post_format'   => $post_format,
            'post_URL'   => $post_URL
        ) );
        add_post_meta($new_post_id, 'url', $post_URL, true);
        
        if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_post']) && $_POST['action_post'] == 'post') {
          set_post_format($new_post_id , standard);
        } else if('POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action_link']) && $_POST['action_link'] == 'post' ) {
          set_post_format($new_post_id , link);
        }
        
        
        
        if (is_single()) {
          // Update parent post
            $current_post = array();
            $current_post['ID'] = $post_parent;
          // Update the post into the database
            wp_update_post( $current_post );
        }
        
        
        // $primary_tag = array_slice($tags, 1, 1);
        
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

// the post form
function writer() {
  if(is_user_logged_in() && function_exists(writer) && current_user_can('publish_posts')) { 
    echo "\n\t".'<div id="writer">'."\n\t";
        if (isset($_GET['writerDraft'])) { 
            echo '<div id="writerDraftNotice">'
                 .'Post saved as draft. '
                 .'<a href="'.get_bloginfo('wpurl').'/wp-admin/edit.php?post_status=draft">'
                 .'View drafts</a>.</div>';
        }
        global $current_user;
        $user = get_userdata($current_user->ID);
        $nickname = attribute_escape($user->nickname);
        
        // Post Parent
        if (is_single()){
          global $post;
          $currentid = $_GET['post'];
          $post_parent = $post->ID;
          // $post_parent = $post->post_parent;
        }?>
        
        
        <div id="writer-box">
          
          <ul id="writer-tools" class="hidden">
            <li><a class="" href="#write-post" class="post_format">text</a></li>
            <li><a class="" href="#write-link" class="post_format">link</a></li>
            <li><a class="" href="#write-file" class="post_format">file</a></li>
            <li id="hide_writer"><a href="#" class="post_format hide_writer">close <span>×</span></a></li>
          </ul>
          
          <div id="write-post">
            
            <form name="new-post" method="post" action="">
              <!-- post -->
              <input type="hidden" name="action_post" value="post" />
              <?php wp_nonce_field( 'new-post' ); ?>
              
              <!-- Post format -->
              <?php //set_post_format($new_post_id , standard); ?>
              <!-- <input type="hidden" name="post_format" tabindex="1" value="standard"/> -->
              
              <!-- Post Parent -->
              <input type="hidden" name="post_parent" id="post_parent" tabindex="1" value="<?php echo $post_parent; ?>"/>
              
              <!-- Topic -->
              <?php if (is_tag()){ ?>
              <h1 class="topic"><?php echo writerTagCheck(); ?></h1>
              <input type="text" name="tags" id="tags" tabindex="1" value="<?php echo writerTagCheck(); ?>" autocomplete="on" />
              <?php } ?>
              
              <!-- Category -->
              <?php if (is_home()){
                $catselect = writerCatCheck();
                echo '<input checked="checked" type="hidden" value="'.$catselect.'" name="catsdd" id="catsdd">';
              } ?>

              <!-- Title -->
              <input type="text" name="post_title" id="post_title" tabindex="1" value="" />
              
              <!-- text -->
              <textarea name="postText" class="wp-editor-wrap" id="postText" tabindex="2" ></textarea>
              
              
              <!-- Post URL -->
              <a href="#" class="post_URLLabel" title="Make this post about a link">URL</a>
              <input type="text" name="no_post_URL" value="http://" class="post_URL" tabindex="3" />

              <input checked="checked" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="writerUrl" >

              <!-- Submit -->
              <input id="submit_post" class="submit" type="submit" value="Post it" />           
            </form>
            
          </div><!-- #write-post -->
          
          
          <div id="write-link" class="hidden">

            <form name="new-link" method="post" action="">

              <!-- post -->
              <input type="hidden" name="action_link" value="post" />
              <?php wp_nonce_field( 'new-post' ); ?>
              
              <!-- Post URL -->
              <a href="#" class="post_URLLabel" title="Make this post about a link">URL</a>
              <input type="text" name="post_URL" value="http://" class="post_URL" tabindex="3" />
              <p class="sourceurl"></p>
              
              <!-- Post Parent -->
              <input type="hidden" name="post_parent" id="post_parent" tabindex="1" value="<?php echo $post_parent; ?>"/>

              <!-- Title -->
              <input type="hidden" name="post_title" id="post_title" tabindex="1" value="" />

              <!-- text -->
              <textarea name="postText" id="postText" tabindex="2" ></textarea>
              
              <!-- page title -->
              <?php echo link_page_title($link); ?>
              
              <!-- Topic -->
              <?php if ($options['tags'] == "on") { ?>
              <!-- <label for="tags" id="tagsLabel">On</label> -->
              <input type="text" name="tags" id="tags" tabindex="1" value="<?php echo writerTagCheck(); ?>" autocomplete="on" />

              <?php } else {
                  $tagselect = writerTagCheck();
                  echo '<input checked="checked" type="hidden" value="'
                        .$tagselect.'" name="tags" id="tags">';
              } ?>

              <input checked="checked" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="writerUrl" >

              <!-- Submit -->
              <input id="submit_link" class="submit" type="submit" value="Post it" />           
            </form>
            
          </div><!-- #write-link -->
          
          
          <div id="write-file" class="hidden">
            <div id="file_upload">
                <form action="<?php bloginfo('url'); ?>/../uploader/upload.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="file[]" multiple>
                    <button type="submit">Upload</button>
                    <div class="file_upload_label">Upload files</div>
                </form>
                <table class="files">
                    <tr class="file_upload_template" style="display:none;">
                        <td class="file_upload_preview"></td>
                        <td class="file_name"></td>
                        <td class="file_size"></td>
                        <td class="file_upload_progress"><div></div></td>
                        <td class="file_upload_start"><button>Start</button></td>
                        <td class="file_upload_cancel"><button>Cancel</button></td>
                    </tr>
                    <tr class="file_download_template" style="display:none;">
                        <td class="file_download_preview"></td>
                        <td class="file_name"><a></a></td>
                        <td class="file_size"></td>
                        <td class="file_download_delete" colspan="3"><button>Delete</button></td>
                    </tr>
                </table>
                <div class="file_upload_overall_progress"><div style="display:none;"></div></div>
                <div class="file_upload_buttons">
                    <button class="file_upload_start">Start All</button> 
                    <button class="file_upload_cancel">Cancel All</button> 
                    <button class="file_download_delete">Delete All</button>
                </div>
            </div>
            
            <?php
              // $script_dir = bloginfo('url');
              // $script_dir = dirname(__FILE__);
              $script_dir = ABSPATH;
              $script_dir = $script_dir.'..';
              
              $script_dir_url = dirname($_SERVER['PHP_SELF']);
              $script_dir_url = $script_dir_url.'/..';
              // echo $script_dir.'<br/>';
              // echo $script_dir_url;
            ?>
            
          </div>
          
        </div>
        
      </div> <!-- close writer -->
        <?php

    }
}



// remove action if loop is in sidebar, i.e. recent posts widget
function removewriterInSidebar() {
    remove_action('loop_start', writer);
}


  

// Blatant copying from p2 here
function writer_ajax_tag_search() {
    global $wpdb;
    $s = $_GET['q'];
    if ( false !== strpos( $s, ',' ) ) {
        $s = explode( ',', $s );
        $s = $s[count( $s ) - 1];
    }
    $s = trim( $s );
    if ( strlen( $s ) < 2 )
        die; // require 2 chars for matching

    $results = $wpdb->get_col( "SELECT t.name 
        FROM $wpdb->term_taxonomy 
        AS tt INNER JOIN $wpdb->terms 
        AS t ON tt.term_id = t.term_id 
        WHERE tt.taxonomy = 'post_tag' AND t.name 
        LIKE ('%". like_escape( $wpdb->escape( $s )  ) . "%')" );
    echo join( $results, "\n" );
    exit;
}

// pass wpurl from php to js
function writer_jsvars() {
    ?><script type='text/javascript'>
    // <![CDATA[
    var ajaxUrl = "<?php echo js_escape( get_bloginfo( 'wpurl' ) . '/wp-admin/admin-ajax.php' ); ?>";
    //]]>
    </script><?php
}


/*
 * SETTINGS
 *
 * - 2.7 and up can modify these in Settings -> Writing -> writer Settings
 *
 * - pre-2.7, the default options are added to the db properly,
 * but the user cannot change this. (well, they can modify the array in db manually...)
 *
 */

// add default fields to db if db is empty
function writerAddDefaultFields() {
    
    // fields that are on by default:
    $fields = array('title', 'tags', 'url', 'categories', 'draft', 'greeting and links'); 

    // fill in options array with each field on
    $options = array();
    foreach($fields as $field) {
        $options[$field] = "on";
    }

    // add the hidden value too
    $options['hidden'] = "on";

    // now add options to the db 
    add_option('writer_fields', $options, '', 'yes');
}


// Only load the next three functions if using 2.7 or higher:
global $wp_version;
if ($wp_version >= '2.7') {
    // add_settings_field
    function writerSettingsInit() {
        // add the section
        add_settings_section(
            'writer_settings_section', 
            'Writer Settings', 
            'writerSettingsSectionCallback', 
            'writing'
        );

        // add 'display on' option
        add_settings_field(
            'writer_homefeed', 
            'Define Home Page Feed',
            'writerHomeFeedCallback',
            'writing',
            'writer_settings_section'
        );
        register_setting('writing','writer_homefeed');

        
    }

    // callback with section description for new writing section
    function writerSettingsSectionCallback() {
      echo "<p>The settings below affect the behavior of the "
          ."<a href=\"http://wordpress.org/extend/plugins/writer/\">writer</a> "
          ."plugin.</p>";
    }


    function writerHomeFeedCallback() {
        // get current values
        if(!$select = get_option('writer_homefeed'))
            $select = 'front';

        $options = array(
                'catheader' => 'Select a category:'
            );

        $cats = get_categories(array(
                    // 'hide_empty' => 0,
                    'hierarchical' => 0
                ));

        foreach($cats as $cat){
            $options[$cat->cat_ID] = $cat->cat_name;
        }


        // build the dropdown menu
        echo '<select name="writer_homefeed" id="writer_homefeed">';

        foreach($options as $key=>$value) {
            if ($select == $key)
                $selected = ' selected="selected"';
            if ($key == 'catheader')
                $disabled = ' disabled="disabled"';
            echo "<option value=\"$key\"$selected$disabled>$value</option>\n";
            unset($selected,$disabled);
        }   

        echo '</select>';

    }
}



/************
 * ACTIONS 
 ************/
// add header content
add_action('get_header', writerHeader);
// add form at start of loop
// add_action('loop_start', writer); 
// don't display form in sidebar loop (i.e. 'recent posts')
// add_action('get_sidebar', removewriterInSidebar);
// add the css
add_action('wp_head', addwriterStylesheet);
// add js
add_action('init', autoResizeJs);
// tell wp-admin.php about ajax tags function with wp_ajax_ action
add_action('wp_ajax_writer_ajax_tag_search', 'writer_ajax_tag_search');
// load php vars for js
add_action('wp_head', 'writer_jsvars');
// add options to "Writing" admin page in 2.7 and up
if ($wp_version >= '2.7') { add_action('admin_init', writerSettingsInit); }

?>