<?php
/*
Plugin Name: Navigation Toggle 4 Divi
Plugin URI: https://celox.io
Description: Toggle navigation menu when the user clicks outside.
Version: 0.0.3
Author: Martin Pfeffer
Author URI: https://celox.io
License: Apache 2.0


Copyright (c) 2017 Martin Pfeffer

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

      http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/

add_action("admin_init", "clx_dnt_init");

add_action("admin_menu", "clx_menu_item");
// Add hook for front-end <head></head>
add_action('wp_head', 'clx_dnt_injecter');


function clx_dnt_init() {
    add_settings_section("setup", "Setup", null, "clx_dnt");
    add_settings_field("clx_dnt_cbx", "Toggle Navigation", "clx_dnt_checkbox", "clx_dnt", "setup");
    add_settings_field("clx_dnt_duration", "Animation duration (ms)", "clx_dnt_input", "clx_dnt", "setup");
    register_setting("setup", "clx_dnt_cbx");
    register_setting("setup", "clx_dnt_duration");
}


function clx_dnt_checkbox() {
    ?>
  <!-- Here we are comparing stored value with 1. Stored value is 1 if user checks the checkbox otherwise empty string. -->
  <input type="checkbox" name="clx_dnt_cbx"
         value="1" <?php checked(1, get_option('clx_dnt_cbx'), true); ?> />
    <?php
}


function clx_dnt_input() {
    ?>
  <input type="text" name="clx_dnt_duration"
         value="<?php echo get_option('clx_dnt_duration', 700); ?>">
    <?php
}


function clx_dnt_page() {
    ?>
  <div class="wrap">
    <h1>Navigation Toggle 4 Divi</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields("setup");
        do_settings_sections("clx_dnt");
        submit_button();
        ?>
    </form>
  </div>
    <?php
}


function clx_menu_item() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient pilchards to access this page.'));
    }
    add_submenu_page("options-general.php", "Nav-Toggle 4 Divi", "Nav-Toggle 4 Divi", "manage_options", "clx-dnt", "clx_dnt_page");
}


/**
 * inject javascript into head
 */
function clx_dnt_injecter() {
    $duration = get_option('clx_dnt_duration');
    if (get_option('clx_dnt_cbx')) {
        // make divi menu toggling on clicked outside
        echo '<script type="text/javascript">(function(a){a("html").click(function(){var c=a(".mobile_nav");if(c.hasClass("opened")){c.removeClass("opened");c.addClass("closed");var b=a("#mobile_menu");b.slideToggle(' . $duration . ')}})})(jQuery);</script>';
    }
}