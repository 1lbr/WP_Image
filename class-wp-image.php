<?php

class WP_Image {
	private $editor;

	private $file;
	private $url;
	private $meta;
	private $backup_sizes;

	function __construct( $post_id ) {
		if ( ! wp_attachment_is_image( $post_id ) )
			return false;

		$this->editor       = new WP_Image_Editor();

		$this->file         = get_attached_file( $post_id );
		$this->url          = wp_get_attachment_url( $post_id );
		$this->meta         = wp_get_attachment_metadata( $post_id );
		$this->backup_sizes = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );
	}

	function get_metadata() {
		return $this->meta;
	}

	function load() {
		return $this->editor->load( $this->file );
	}

	function resize( $max_w, $max_h, $crop = false, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {
		return $this->resize->load( $max_w, $max_h, $crop, $suffix, $dest_path, $jpeg_quality );
	}
}