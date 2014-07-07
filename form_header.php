<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>2014/2015 Getting To Know Your Child</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700' rel='stylesheet' type='text/css'>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

		<link rel="stylesheet" href="css/base.css">

		<?php 
			if(isset($filled) && $filled):
		?>
			<!-- This is a filled-out form -->
			<link rel="stylesheet" href="css/filled.css">
		<?php
			else:
		?>
			<!-- This is a blank form -->
			<link rel="stylesheet" href="css/bootstrapValidator.min.css">
			<script src="js/bootstrapValidator.min.js"></script>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#main_form').bootstrapValidator();
				});
			</script>
		<?php 
			endif;
		?>

	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->