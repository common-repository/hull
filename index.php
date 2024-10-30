<?php
/**
 * @package Hull
 * @version 0.1
 */
/*
Plugin Name: Hull
Plugin URI: http://github.com/hull/hull-wordpress
Description: Hull.io integration with your Wordpress
Author: Stephane Bellity, Xavier Cambar
Version: 0.1.0
Author URI: http://github.com/hull/hull-wordpress
*/

define('HULL_VERSION', '0.6.3');
define('HULL_PLUGIN_URL', plugin_dir_url( __FILE__ ));

require_once(plugin_dir_path(__FILE__) . 'hull-php/src/Hull/Client.php');

wp_register_script('hull.js', "//hull-js.s3.amazonaws.com/" . HULL_VERSION . "/hull.js", array('jquery'), false, false);
wp_register_script('hull-init.js', HULL_PLUGIN_URL . "/hull-init.js", array('hull.js'), false, false);

function hull_init() {
  wp_enqueue_script('hull-init.js');
}

function hull_init_config() {
  $hull_config = array(
    'hull'    => array(
      'appId'   => get_option('hull_app_id'),
      'orgUrl'  => get_option('hull_org_url'),
      'debug'   => get_option('hull_debug'),
    ),
    'blogUrl' => get_bloginfo('url'),
    'logoutUrl' =>wp_logout_url(get_permalink()),
    'loginUrl' =>wp_login_url(get_permalink())

  );
  echo "<script type='text/javascript'>window.HullConfig = " . json_encode($hull_config) .";</script>";
}

add_action( 'wp_enqueue_scripts', 'hull_init_config' );
add_action( 'wp_enqueue_scripts', 'hull_init' );

// add_action('wp_head', 'hull_init');

// Auto load hbs templates
add_action('wp_footer', 'hull_include_templates');

function hull_include_templates() {
  // echo Hull_Helpers::includeWidgets(get_template_directory() . '/hull/widgets/');
  // echo Hull_Helpers::includeTemplates(get_template_directory() . '/hull/templates/');
}

function get_hull_client() {
  $config = array();
  $config['hull'] = array(
    'appId'     => get_option('hull_app_id'),
    'host'      => get_option('hull_org_url'),
    'appSecret' => get_option('hull_app_secret')
  );
  return new Hull_Client($config);
};

function get_hull_options() {
  return array(
    'hull_app_id'     => array('name' => 'App ID', 'default' => '', 'autoload' => 'yes'),
    'hull_org_url'    => array('name' => 'Org URL', 'default' => '', 'autoload' => 'yes'),
    'hull_app_secret' => array('name' => 'App Secret', 'default' => '', 'autoload' => 'no'),
    'hull_debug'      => array('name' => 'Debug', 'default' => 'false', 'values' => array('true', 'false'),  'autoload' => 'no', 'type' => 'select')
  );
}

foreach(get_hull_options() as $key => $opt) {
  add_option($key, $opt['default'], "", $opt['autoload']);
  if (isset($_POST[$key])) {
    update_option($key, $_POST[$key]);
  }
}


// Admin UI Config page

add_action( 'admin_menu', 'hull_plugin_menu' );

function hull_plugin_menu() {
  add_options_page( 'Hull Options', 'Hull', 'manage_options', 'hull-options', 'hull_plugin_options' );
}

function hull_plugin_options() {
  hull_render_plugin_options("Hull Config", get_hull_options());
}

function hull_render_plugin_options($sectionName, $optsList, $hint='') {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  echo '<form name="hull_options_form" method="post" action="">';
  echo '<h1>' . $sectionName . '</h1>';
  echo '<p>' . $hint . '</p>';
  echo '<table class="form-table"><tbody>';
  foreach($optsList as $key => $opt) {
    $val = get_option($key);
    echo '<tr><th scope="row">' . $opt['name'] . '</th><td>';
    if (isset($opt['values'])) {
      echo '<select name="' . $key . '">';
      foreach($opt['values'] as $v) {
        if ($v == $val) {
          $selected = 'selected';
        } else {
          $selected = '';
        }

        echo '<option ' . $selected . '>' . $v . '</option>';
      }
      echo '</select>';
    } elseif (isset($opt['type']) && $opt['type'] == 'textarea') {
      echo '<textarea name="' . $key . '">' . $val .'</textarea>';
    } else {
      echo '<input type="text" name="' . $key . '" value="' . $val . '" size="36">';
      if (strlen($opt['default']) > 0) {
        echo '<p><strong>default:</strong> ' . $opt['default'] . '</p>';
      }
    }
    if (strlen($opt['hint']) > 0) {
      echo '<p>' . $opt['hint'] . '</p>';
    }
    echo '</td></tr>';
  }
  echo '</tbody></table>';
  echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>';
  echo '</form>';
}


// Admin UI Post Meta

add_action( 'add_meta_boxes', 'hull_add_custom_box' );
function hull_add_custom_box() {
  $screens = array( 'post', 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'hull_meta',
            'Hull',
            'hull_meta_box',
            $screen
        );
    }
}

function hull_meta_box($post) {
  $hull_id = get_post_meta($post->ID, 'hull_id', true);
  echo "<strong>HullID</strong> " . $hull_id;
}

// Helper functions

function hull_comments_widget($post_id, $options=array()) {
  $hull_id = get_post_meta($post_id, 'hull_id', true);
  hull_widget("comments", array_merge($options, array("id" => $hull_id)));
}

function hull_reviews_widget($post_id, $options=array()) {
  $hull_id = get_post_meta($post_id, 'hull_id', true);
  hull_widget("reviews", array_merge($options, array("id" => $hull_id)));
}


function hull_widget($name, $options=array(), $tagName = "div", $placeholder="") {
  $prms = array('data-hull-widget="' . $name . '"');
  foreach ($options as $key => $val) {
    $prms[] = 'data-hull-' . $key . '="'. $val .'"';
  }
  echo "<$tagName " . implode(" ", $prms) . ">$placeholder</$tagName>";
}

require_once(implode(array(dirname(__FILE__), 'auth.php'), '/'));

