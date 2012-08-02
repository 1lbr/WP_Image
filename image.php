<?php

function wp_get_image_for_editing( $file ) {
	if( is_numeric( $file ) )
		$file = get_attached_file( $file );

	$class = new WP_Image_Editor( $file );

	return $class;
}