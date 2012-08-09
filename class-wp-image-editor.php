<?php

class WP_Image_Editor {
	private $file;
	private $editor;

	function __construct( $file ) {
		$this->file = $file;

		$config = get_option('image_engine_opts');

		$this->opts = array(
			"max_width" => 		$config->max_width || 300,
			"max_height" => 	$config->max_height || 300,
			"crop" => 			$config->crop || false,
			"suffix" => 		$config->suffix || null,
			"path" => 			$config->path || null,
			"quality" => 		$config->quality || 90
		);
	}

	/**
	 * check which engine will be used, must be optional
	 *
	 * @since 3.5.0
	 * @access private
	 *
	 * @return string Engine (GD/ImageMagick/GraphicsMagick)
	 */
	private function get_engine() {
		$class = 'WP_Image_Editor_' . get_option('image_engine', 'GD');

		return $this->editors[ $class ];
	}

	function resize() {
		$editor = $this->get_engine();
		return $editor->resize( $this->file, $this->opts );
	}

	function rotate() {

	}

	function save() {

	}
}