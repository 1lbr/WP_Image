<?php

class WP_Image_Editor {
	private $file;
	private $editors;

	function __construct( $file ) {
		$this->file = $file;
	}

	/**
	 * Tests which editors are capable of supporting the request.
	 *
	 * @since 3.5.0
	 * @access private
	 *
	 * @return string|bool Class name for the first editor that claims to support the request. False if no editor claims to support the request.
	 */
	private function get_first_available( $function ) {
		$request_order = apply_filters( 'wp_editors', array( 'imagick', 'gd' ) );

		// Loop over each editor on each request looking for one which will serve this request's needs
		foreach ( $request_order as $editor ) {
			$class = 'WP_Image_Editor_' . $editor;

			// Check to see if this editor is a possibility, calls the editor statically
			if ( ! call_user_func( array( $class, 'test' ), $function  ) )
				continue;

			if( ! apply_filters( 'wp_editor_use_' . $editor, true, $function ) )
				continue;

			if( ! $this->editors[ $class ] ) 
				$this->editors[ $class ] = new $class;

			return $this->editors[ $class ];
		}

		return false;
	}


	function load( $file ) {
		$editor = $this->get_first_available( 'load' );

		if( $editor ) {
			return $editor->load( $file );
		}

		return false;
	}

	function resize( $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		$editor = $this->get_first_available( 'resize' );

		if( $editor ) {
			return $editor->resize( $this->file, $max_w, $max_h, $crop, $suffix, $dest_path, $jpeg_quality );
		}
	}

	function rotate() {

	}

	function save() {

	}
}