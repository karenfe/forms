<?php

	if(isset($_GET['file'])){
		$file = str_replace("..", "", $_GET['file']);
	}else{
		die();
	}
	
	include "form_header.php";

	include "forms/$file";

	include "form_footer.php";

?>