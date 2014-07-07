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

		add_action( 'init', array($this, 'init' ));
		
	}

	private function init(){
		
	}

	public static function install() {
		$path = strstr(WP_PLUGIN_DIR,"wp-content")."/".FormStorage::$plugin_name."/";
		add_rewrite_rule('form/([^/]*)$', $path.'show_form.php?file=$1', 'top');
		add_rewrite_rule('form/js/([^/]*)', $path.'js/$1', 'top');
		add_rewrite_rule('form/css/([^/]*)', $path.'css/$1', 'top');

		add_rewrite_rule('filled/([^/]*)$', $path.'show_filled_form.php', 'top');
		add_rewrite_rule('filled/js/([^/]*)', $path.'js/$1', 'top');
		add_rewrite_rule('filled/css/([^/]*)', $path.'css/$1', 'top');

		flush_rewrite_rules(true);
	}
	
}

new FormStorage();

register_activation_hook( __FILE__, array( 'FormStorage', 'install' ) );

?>