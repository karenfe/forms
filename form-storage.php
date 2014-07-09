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

require_once("functions.php");

class FormStorage{

	public static $plugin_name = 'form-storage-and-email';
	public static $plugin_name_short = 'form-storage';
	public static $table_name = 'form_submissions';

	public static $plugin_name_capitalized = "Forms and Storage";

	public function __construct(){

		add_action( 'init', 		array($this, 'init' ));
		add_action( 'admin_menu', 	array($this, 'prepare_menus'));
		add_action( 'wp_enqueue_scripts', 		array($this, 'prepare_javascript_stylesheet') );
		add_action( 'admin_post_delete_forms', 	array( $this, 'delete_submissions') );
		add_filter( 'admin_init', 				array($this, 'process_queries'), 1 );

	}

	public function init(){
		//add_shortcode('include_form', array($this, 'include_form'));

	}

	public function prepare_menus(){
		// Add to menu
		//$page = add_menu_page( FormStorage::$plugin_name_capitalized, FormStorage::$plugin_name_capitalized, 'administrator', $this->plugin_name, array($this, 'render_admin_page'));
		//$page = add_utility_page( FormStorage::$plugin_name_capitalized, FormStorage::$plugin_name_capitalized, 'administrator', $this->plugin_name, array($this, 'render_admin_page'));
		add_menu_page( FormStorage::$plugin_name_capitalized, FormStorage::$plugin_name_capitalized, 'administrator', FormStorage::$plugin_name_short.'/admin-page.php', '', plugins_url( FormStorage::$plugin_name_short.'/img/icon.png' ));
		add_action("admin_print_scripts-$page", array($this, 'prepare_javascript_stylesheet'));
	}

	public function process_queries(){
		if ( ! current_user_can( 'manage_options' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			wp_die( __( 'You are not allowed to access this part of the site' ) );
		}
		if($_GET['action'] == "delete"){
			$deleted = TRUE;
			check_admin_referer( 'delete_'.$_GET['id'] );
			global $wpdb;
			$sql = $wpdb->prepare( "DELETE FROM `".$wpdb->prefix . FormStorage::$table_name."` WHERE id = %d", $_GET['id'] );
			if($wpdb->query($sql)){
				$error = FALSE;
			}
		}
	}

	public function render_admin_page(){
		include "admin-page.php";
	}

	public function delete_form_submission(){

	}

	public function prepare_javascript_stylesheet(){

		/*
		wp_enqueue_script($this->plugin_name_short.'shortcode-javascript', plugins_url('js/shortcode_main.js', __FILE__ ).'?r='.rand());

		wp_enqueue_style($this->plugin_name_short.'shortcode-bootstrap', "//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css");
		wp_enqueue_style($this->plugin_name_short.'shortcode-bootstrap-theme', "//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css");
		wp_enqueue_style($this->plugin_name_short.'shortcode-base-stylesheet', plugins_url('css/shortcode_base.css', __FILE__ ).'?r='.rand());
		*/

		wp_enqueue_script($this->plugin_name_short.'-javascript', plugins_url('js/admin_main.js', __FILE__ ).'?r='.rand());
		wp_enqueue_style($this->plugin_name_short.'-stylesheet', plugins_url('css/admin_style.css', __FILE__ ).'?r='.rand());
	}


	// public function include_form($atts){

	// $attributes = shortcode_atts( array(
	// 		'file' => ''
	// 	), $atts );

	// 	$file = $attributes['file'];
	// 	$html = "\"".$file."\" Doesnt' exist.";
	// 	if($file != "" && file_exists(dirname(__FILE__)."/forms/".$file)){
	// 		$html = get_include_contents(dirname(__FILE__)."/forms/".$file);
	// 	}
	// 	return $html;
	// }


	public static function install() {
		
		$path = strstr(WP_PLUGIN_DIR,"wp-content")."/".FormStorage::$plugin_name_short."/";
		
		add_rewrite_rule('form/submit[/]?$', $path.'submit-form.php?file=$1', 'top');

		add_rewrite_rule('form/js/([^/]*)', $path.'js/$1', 'top');
		add_rewrite_rule('form/css/([^/]*)', $path.'css/$1', 'top');
		add_rewrite_rule('form/([^/]*)$', $path.'show-form.php?file=$1', 'top');

		flush_rewrite_rules(true);


		global $wpdb;
		global $jal_db_version;

		$table_name = $wpdb->prefix . FormStorage::$table_name;

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT, 
			time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			file text, 
			data text, 
			UNIQUE KEY id (id)
		) DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	public static function uninstall() {

        global $wpdb;
        $table = FormStorage::$table_name;

		$wpdb->query("DROP TABLE IF EXISTS $table");

		flush_rewrite_rules(true);

	}
	
}

new FormStorage();

register_activation_hook( __FILE__, array( 'FormStorage', 'install' ) );
register_deactivation_hook( __FILE__, array( 'FormStorage', 'uninstall' ) );

?>