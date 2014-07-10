<?php


	if(isset($_GET['file']) && $_GET['file'] != ""){
		$file = str_replace("..", "", $_GET['file']);
		if(!file_exists("./forms/".$file)){
			die();
		}
	}else{
		die();
	}
	
	include "form_header.php";

	include "forms/$file";

	include "form_footer.php";

?>