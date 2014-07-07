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

	public static $plugin_name = 'form-storage-and-email';

	private $plugin_name_capitalized = "Forms and Storage";
	

	public function __construct(){

		add_action( 'init', array($this, 'init' ));
		
	}

	private function init(){
		add_action('admin_menu', array($this, 'prepare_menus'));
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

	public function prepare_menus(){
		// Add to menu
		$page = add_options_page( $this->plugin_name_capitalized, $this->plugin_name_capitalized, 'administrator', $this->plugin_name, array($this, 'render_admin_page'));
		add_action("admin_print_scripts-$page", array($this, 'prepare_javascript_stylesheet'));
	}

	public function prepare_javascript_stylesheet(){
		wp_enqueue_script($this->plugin_name_short.'-javascript', plugins_url('js/admin_main.js', __FILE__ ).'?r='.rand());
		wp_enqueue_style($this->plugin_name_short.'-stylesheet', plugins_url('css/admin_style.css', __FILE__ ).'?r='.rand());
	}
	
}

new FormStorage();

register_activation_hook( __FILE__, array( 'FormStorage', 'install' ) );

?>