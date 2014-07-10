<?php

	error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

	if(!isset($_GET['show']) || $_GET['show']==""){
		die();
	}

	require_once("functions.php");

	includeWP();

	// if(!current_user_can( 'manage_options' )){
	 	//header('HTTP/1.0 403 Forbidden');
	// 	die();
	// }


	$header = get_include_contents("form_header.php");
	$footer = get_include_contents("form_footer.php");

	global $wpdb;

	$sql = $wpdb->prepare( 'SELECT * FROM `'.$wpdb->prefix . FormStorage::$table_name.'` WHERE link=%s ORDER BY time LIMIT 1', $_GET['show']);
	
	$row = $wpdb->get_row($sql,OBJECT);

	if($row){
		$file_content = file_get_contents("forms/".$row->file);

		$html = fillForm($file_content, deserializeArray($row->data), "big_checkmark", TRUE, TRUE);

		require_once("lib/CssToInlineStyles.php");

		$full = $header.$html.$footer;
		echo $full;

		/*
		$inline = new CssToInlineStyles();

		$inline->setHTML($full);
		$css 	= file_get_contents('./css/bootstrap.min.css')."\n";
		$css   .= file_get_contents('./css/form_base.css');
		$inline->setCSS($css);

		$output = $inline->convert();
		echo $output;

		$to = 'tog000@gmail.com';

		$subject = 'Test submit form';

		$headers = "From: " . strip_tags("tog000@gmail.com") . "\r\n";
		$headers .= "Reply-To: ". strip_tags("tog000@gmail.com") . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		mail($to, $subject, $output, $headers);
		*/

		
		/*
		require_once("lib/html2pdf_v4.03/html2pdf.class.php");

		$output = str_replace("font-family", "ignore", $output);

		$html2pdf = new HTML2PDF('P','A4','en');
		
    	$html2pdf->WriteHTML($output);
    	$html2pdf->Output('/tmp/exemple.pdf',"F");
    	*/

	}
?>