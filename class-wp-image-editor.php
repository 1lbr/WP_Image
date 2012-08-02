<?php

class WP_Image_Editor {
	function __construct( $file_path ) {

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
		$request_order = apply_filters( 'wp_editors', array( 'imagemagick', 'gd' ) );

		// Loop over each editor on each request looking for one which will serve this request's needs
		foreach ( $request_order as $editor ) {
			$class = 'WP_Image_Editor_' . $editor;

			// Check to see if this editor is a possibility, calls the editor statically
			if ( ! call_user_func( array( $class, 'test' ), $function  ) )
				continue;

			if( ! apply_filters( 'wp_editor_use_' . $editor, true, $function ) )
				continue;

			return $class;
		}

		return false;
	}

	function resize() {

	}

	function rotate() {

	}

	function save() {

	}
}