<?php

class WP_Image_Editor_Imagick {
	function __construct() {
	}

	// if both tests fall, use GD or Warning the user...
	public static function test_system_call(){
		if ( false !== strpos(ini_get("disable_functions"), "exec") )
        	return false;
    
		return true;
	}

	public static function test_extension( $function ) {
		if ( ! extension_loaded('imagick') )
			return false;

		return true;
	}

	public function load( $file ) {
		if ( ! file_exists( $file ) )
			return sprintf( __('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file );

		try {
			$image = new Imagick( $file );
		}
		catch ( Exception $e ) {
			return sprintf(__('File &#8220;%s&#8221; is not an image.'), $file);
		}

		if( ! $image->valid() ) {
			return sprintf(__('File &#8220;%s&#8221; is not an image.'), $file);
		}

		return $image;
	}

	public function resize( $file, $opts ) {
		$image = $this->load( $file );

		if ( ! is_object( $image ) )
			return new WP_Error( 'error_loading_image', $image, $file );

		$imageprops = $image->getImageGeometry();
		$orig_type  = $image->getImageFormat();
		if ( ! $imageprops )
			return new WP_Error( 'invalid_image', __('Could not read image size'), $file );

		$dims = image_resize_dimensions( $imageprops['width'], $imageprops['height'], $max_w, $max_h, $crop );
		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions') );
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		if( 'JPEG' == $orig_type ) {
			$image->setImageCompression( imagick::COMPRESSION_JPEG );
			$image->setImageCompressionQuality( $jpeg_quality );
		}

		if ( $crop ) {
			$image->cropImage( $src_w, $src_h, $src_x, $src_y );
		}

		//$image->thumbnailImage( $dst_w, $dst_h );
		$image->scaleImage( $dst_w, $dst_h, true );

		// $suffix will be appended to the destination filename, just before the extension
		if ( ! $suffix )
			$suffix = "{$dst_w}x{$dst_h}";

		$info = pathinfo( $file );
		$dir  = $info['dirname'];
		$ext  = $info['extension'];
		$name = wp_basename( $file, ".$ext" );

		if ( ! is_null( $dest_path ) && $_dest_path = realpath( $dest_path ) )
			$dir = $_dest_path;
		$destfilename = "{$dir}/{$name}-{$suffix}.{$ext}";

		if( apply_filters( 'wp_editors_stripimage', true ) ) {
			$image->stripImage();
		}

		$image->writeImage( $destfilename );

		// Set correct file permissions
		$stat = stat( dirname( $destfilename ) );
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $destfilename, $perms );

		return array(
			'path' => $destfilename,
			'file' => wp_basename(  apply_filters( 'image_make_intermediate_size', $destfilename ) ),
			'width' => $dst_w,
			'height' => $dst_h
		);
	}
}