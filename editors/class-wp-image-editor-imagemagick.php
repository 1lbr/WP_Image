<?php

class WP_Image_Editor_Imagemagick {
	function __construct() {

	}

	public static function test( $function ) {
		if ( ! extension_loaded('imagick') )
			return false;

		return true;
	}

	public function load( $file ) {
		if ( ! file_exists( $file ) )
			return sprintf( __('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file );

		$image = null;

		if ( ! is_resource( $image ) )
			return sprintf(__('File &#8220;%s&#8221; is not an image.'), $file);

		return $image;
	}

	public function resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$image = $this->load( $file );
		if ( ! is_resource( $image ) )
			return new WP_Error( 'error_loading_image', $image, $file );

		return null;
	}
}