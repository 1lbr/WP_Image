<?php

function wp_get_image_for_editing( $file_path ) {
	if( is_numeric( $file_path ) ) {
		$filepath = 'magic';
	}

	$class = new WP_Image_Editor( $filepath );

	return $class;
}