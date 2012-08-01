<?php

class WP_Image_Editor_Imagemagick {
	function __construct() {

	}

	function test( $function ) {
		if ( ! extension_loaded('imagick') )
			return false;

		return true;
	}
}