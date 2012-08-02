<?php

class WP_Image_Editor_GD {
	function __construct() {

	}

	function test( $function ) {
		if ( ! extension_loaded('gd') || ! function_exists('gd_info') )
			return false;

		return true;
	}
}