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

	private function editor() {
		if( $this->editor )
			return $this->editor;

		$this->editor = new WP_Image_Editor( $this->url );

		return $this->editor;
	}
}