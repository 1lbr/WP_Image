<?php

class WP_Image_Editor_GD {
	private $image = false;

	function __construct() {

	}

	function __destruct() {
		if ( $this->image ) {
			// we don't need the original in memory anymore
			imagedestroy( $image );
		}
	}

	public static function test( $function ) {
		if ( ! extension_loaded('gd') || ! function_exists('gd_info') )
			return false;

		return true;
	}

	public function load( $file ) {
		if( $this->image ) 
			$this->image;

		if ( ! file_exists( $file ) )
			return sprintf( __('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file );

		if ( ! function_exists('imagecreatefromstring') )
			return __('The GD image library is not installed.');

		// Set artificially high because GD uses uncompressed images in memory
		@ini_set( 'memory_limit', apply_filters( 'image_memory_limit', WP_MAX_MEMORY_LIMIT ) );
		$image = imagecreatefromstring( file_get_contents( $file ) );

		if ( ! is_resource( $image ) )
			return sprintf( __('File &#8220;%s&#8221; is not an image.'), $file );

		$this->image = $image;
		return $image;
	}

	public function resize( $file, $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$image = $this->load( $file );
		if ( ! is_resource( $image ) )
			return new WP_Error( 'error_loading_image', $image, $file );

		$size = @getimagesize( $file );
		if ( ! $size )
			return new WP_Error( 'invalid_image', __('Could not read image size'), $file );
		list( $orig_w, $orig_h, $orig_type ) = $size;

		$dims = image_resize_dimensions( $orig_w, $orig_h, $max_w, $max_h, $crop );
		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions') );
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;


		$newimage = wp_imagecreatetruecolor( $dst_w, $dst_h );

		imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

		// convert from full colors to index colors, like original PNG.
		if ( IMAGETYPE_PNG == $orig_type && function_exists('imageistruecolor') && !imageistruecolor( $image ) )
			imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );

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

		if ( IMAGETYPE_GIF == $orig_type ) {
			if ( ! imagegif( $newimage, $destfilename ) )
				return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
		}
		elseif ( IMAGETYPE_PNG == $orig_type ) {
			if ( !imagepng( $newimage, $destfilename ) )
				return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
		}
		else {
			// all other formats are converted to jpg
			if ( 'jpg' != $ext && 'jpeg' != $ext )
				$destfilename = "{$dir}/{$name}-{$suffix}.jpg";

			if ( ! imagejpeg( $newimage, $destfilename, apply_filters( 'jpeg_quality', $jpeg_quality, 'image_resize' ) ) )
				return new WP_Error( 'resize_path_invalid', __( 'Resize path invalid' ) );
		}

		imagedestroy( $newimage );

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