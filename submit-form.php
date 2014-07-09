<?php
	
	if(isset($_POST['file']) && $_POST['file'] != ""){
		$file = str_replace("..", "", $_POST['file']);
		unset($_POST['file']);
	}else{
		die();
	}

	require_once("functions.php");

	includeWP();

	// Sanitize form input
	$file_content = file_get_contents("forms/$file");
	$fields = sanitizeFormFields($file_content, $_POST);
	$serialized = serializeArray($fields);

	global $wpdb;
	$rows_affected = $wpdb->insert( $wpdb->prefix . FormStorage::$table_name, array( 'file' => $file, 'data' => $serialized ) );

?>
