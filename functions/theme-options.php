<?php

  function indieweb_admin_menu() {  
    add_submenu_page('themes.php',   
      'IndieWeb Settings', 'IndieWeb Settings', 'manage_options',   
      'indieweb-settings', 'indieweb_settings');
    
      // Check that the user is allowed to update options  
      if (!current_user_can('manage_options')) {  
        wp_die('You do not have sufficient permissions to access this page. Thanks.');  
      }
  }
  
  function indieweb_settings() { 
    // SAVE THE FORM
    if (isset($_POST["update_settings"])) {  
      $indieweb_profile = stripslashes($_POST["indieweb_profile"]);
      update_option("indieweb_profile", $indieweb_profile);
      echo '<div id="message" class="updated"><p>Settings saved</p></div>';
    }
    
    
    ?>
    <div class="wrap">  
      <?php screen_icon('themes'); ?>
      <h2>IndieWeb Settings</h2>  

      <form method="POST" action="">
        <input type="hidden" name="update_settings" value="Y" />
        <table class="form-table">  
          <tr valign="top">  
            <th scope="row">  
              <label for="indieweb_profile">
                Personal Image
              </label>
            </th>
            <td>
              <input placeholder="http://" name="indieweb_profile" value="<?php echo stripslashes(get_option("indieweb_profile")); ?>" />
            </td>
          </tr>
        </table>
        <p><input type="submit" value="Save settings" class="button-primary"/></p>
      </form>
    </div>
    
  <?php
  }
  



  // This tells WordPress to call the function named "setup_theme_admin_menus"  
  // when it's time to create the menu pages.  
  add_action("admin_menu", "indieweb_admin_menu");

?>