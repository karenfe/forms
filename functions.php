<?php
/**
 * Plugin Name: Form Storage and Email
 * Plugin URI: tog000.io
 * Description: Plugin stores externally generated forms and sends notification emails
 * Version: 1.0
 * Author: Gabriel Trisca
 * Author URI: tog000.io
 * License: MIT
 */


class FormStorage{

	public static $plugin_name = 'form-storage';

	public function __construct(){

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array($this, 'init' ));
		add_action( 'init', array($this, 'init' ));
		
	}

	private function init(){
		register_rewrite();
	}

	private function register_rewrite(){
		$path = strstr(WP_PLUGIN_DIR,"wp-content")."/".FormStorage::plugin_name."/forms/";
		add_rewrite_rule('form/([^/]*)', $path.'$1', 'top');
	}

	private function install() {
		register_rewrite();
		flush_rewrite_rules();
	}

	
	
}

new FormStorage();

?>