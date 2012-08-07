<?php

/**
 * @package WP_Image
 * @version 0.1
 */
/*
Plugin Name: WP_Image
Plugin URI: https://github.com/markoheijnen/WP_Image
Description: This is for testing WP_Image and WP_Image_Editor classes
Author: Marko Heijnen
Version: 0.1
Author URI: http://markoheijnen.com
*/

include 'class-wp-image.php';
include 'class-wp-image-editor.php';
include 'image.php';

include 'editors/class-wp-image-editor-gd.php';
include 'editors/class-wp-image-editor-imagick.php';

class WP_Image_Testsuite {
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'add_menu_page' ) );
	}

	function add_menu_page() {
		add_management_page( 'WP_Image', 'WP_Image', 'manage_options', 'wp-image', array( &$this, 'menu_page' ) );
	}

	function menu_page() {
		if ( ! current_user_can('manage_options') )  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
			return;
		}

		echo '<div class="wrap">';
		echo '<div id="icon-options-general" class="icon32"><br></div>';
		echo '<h2>WP_Image</h2>';


		echo '</div>';
	}
}

new WP_Image_Testsuite;