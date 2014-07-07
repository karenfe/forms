<?php

	$filled = true;
	
	if(isset($_POST['file'])){
		$file = str_replace("..", "", $_POST['file']);
		unset($_POST['file']);
	}else{
		die();
	}
	
	include "form_header.php";

	$file_content = file_get_contents("forms/$file");

	print_r($_POST);

	foreach ($_POST as $key => $value){

		$pos = strpos($file_content, "name=\"$key\"");
		$init = strrpos($file_content, "<", $pos);

		preg_match('@name="'.$key.'"@i',
		    $file_content, $matches);
		
		print_r($matches);


		$file_content = str_replace("name=\"$key\"", "name=\"$key\" readonly value=\"$value\"", $file_content);
		$file_content = str_replace("placeholder","",$file_content);
	}

	echo $file_content;

	include "form_footer.php";

?>