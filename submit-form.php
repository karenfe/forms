<?php
	
	if(isset($_POST['file']) && $_POST['file'] != ""){
		$file = str_replace("..", "", $_POST['file']);
		unset($_POST['file']);
	}else{
		die();
	}

	require_once('lib/recaptchalib.php');

	$privatekey = "6Lf3hvYSAAAAACi9nebjZtmTLGzzxWp_XlEaF21z";
	$resp = recaptcha_check_answer ($privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		die (file_get_contents("bad_captcha.html"));
	}

	require_once("functions.php");

	includeWP();

	// Sanitize form input
	$file_content = file_get_contents("forms/$file");
	$fields = sanitizeFormFields($file_content, $_POST);
	$serialized = serializeArray($fields);

	$link = md5(hexdec(bin2hex(openssl_random_pseudo_bytes(16))));

	global $wpdb;
	$sql = $wpdb->prepare( " INSERT INTO `".$wpdb->prefix . FormStorage::$table_name."` (file,data,link) values(%s,%s,%s)", $file, $serialized, $link);
	$result = $wpdb->query($sql);

	$sql = $wpdb->prepare( " SELECT link FROM `".$wpdb->prefix . FormStorage::$table_name."` WHERE id=%d LIMIT 1", $wpdb->insert_id );
	$object = $wpdb->get_row($sql);
	
	$link = site_url()."/forms/".$object->link;

	$output = file_get_contents("email_template.html");
	$output = str_replace("{{ link }}", $link, $output);
	$output = str_replace("{{ date }}", date("Y-M-d h:ma"), $output);

	//TODO Make this configurable
	$to = 'tog000@gmail.com';

	$subject = 'A form has been filled';

	$headers = "From: " . strip_tags("tog000@gmail.com") . "\r\n";
	$headers .= "Reply-To: ". strip_tags("tog000@gmail.com") . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	mail($to, $subject, $output, $headers);

	header("Location: ".get_page_link(1216));


?>
