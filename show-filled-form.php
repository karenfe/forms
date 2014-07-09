<?php
	
	if(!isset($_GET['show']) || $_GET['show']==""){
		die();
	}

	require_once("functions.php");

	includeWP();

	$header = get_include_contents("form_header.php");
	$footer = get_include_contents("form_footer.php");



	global $wpdb;

	$sql = $wpdb->prepare( 'SELECT * FROM `'.$wpdb->prefix . FormStorage::$table_name.'` WHERE id=%d', $_GET['show']);
	$row = $wpdb->get_row($sql,OBJECT);

	$file_content = file_get_contents("forms/".$row->file);

	$html = fillForm($file_content, deserializeArray($row->data), "big_checkmark", TRUE, TRUE);

	echo $header;
	echo $html;
	echo $footer;
?>