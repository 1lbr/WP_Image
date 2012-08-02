<?php

class WP_Image_Editor_GD {
	function __construct() {

	}

	function load( $file ) {
		if ( ! file_exists( $file ) )
			return sprintf( __('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file );

		if ( ! function_exists('imagecreatefromstring') )
			return __('The GD image library is not installed.');

		// Set artificially high because GD uses uncompressed images in memory
		@ini_set( 'memory_limit', apply_filters( 'image_memory_limit', WP_MAX_MEMORY_LIMIT ) );
		$image = imagecreatefromstring( file_get_contents( $file ) );

		if ( !is_resource( $image ) )
			return sprintf(__('File &#8220;%s&#8221; is not an image.'), $file);

		return $image;
	}

	function test( $function ) {
		if ( ! extension_loaded('gd') || ! function_exists('gd_info') )
			return false;

		return true;
	}
}