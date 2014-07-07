<?php
	
	if(isset($_POST['file'])){
		$file = str_replace("..", "", $_POST['file']);
		unset($_POST['file']);
	}else{
		die();
	}

	$header = get_include_contents("form_header.php");
	$footer = get_include_contents("form_footer.php");

	include "lib/simple_html_dom.php";

	$file_content = file_get_contents("forms/$file");
	$html = str_get_html($file_content);

	foreach ($_POST as $key => $value){

		if(is_array($value)){
			$key .= "[]";
		}

		//echo 'Searching input[name='.$key.']';
		$element = $html->find('input[name='.$key.']',0);
		if($element != null){

			// If we have a radio, search through all of the and check the right one
			if($element->type=="radio"){
				$radios = $html->find('input[name='.$key.']');
				foreach($radios as $radio){
					$radio->disabled = true;
					$radio->checked = false;
					$radio->class .= " big_checkmark";
					if($radio->value == $value){
						$radio->checked = true;
						$radio->parent()->class .= " big_checkmark";
					}
				}
				continue;
			}

			if($element->type=="checkbox"){
				$checkboxes = $html->find('input[name='.$key.']');
				foreach($checkboxes as $checkbox){
					$checkbox->disabled = true;
					$checkbox->checked = false;
					$checkbox->class .= " big_checkmark";
					if(in_array($checkbox->value, $value)){
						$checkbox->checked = true;
						$checkbox->parent()->class .= " big_checkmark";
					}
				}
				continue;
			}

			echo "Setting the value to $value<br/>";
			$element->value = $value;
			continue;

		}

		$element = $html->find('select[name='.$key.']',0);
		if($element != null){
			///print_r($element);
			$options = $element->children();
			
			foreach($options as $option){
				$option->disabled = true;
				$option->selected = false;
				$option->class .= " big_checkmark";
				if($option->value == $value){
					$option->selected = true;
					$option->parent()->class .= " big_checkmark";
				}
			}
			continue;
			
		}


		$element = $html->find('textarea[name='.$key.']',0);
		if($element != null){
			echo "Setting the value to $value<br/>";
			$element->innertext = $value;
			$element->readonly = true;
			continue;
		}

		//$element->readonly = true;

	}

	echo $header;
	echo $html->save();
	echo $footer;

	function endsWith($haystack, $needle){
	    $length = strlen($needle);
	    if ($length == 0) {
	        return true;
	    }

	    return (substr($haystack, -$length) === $needle);
	}

	function get_include_contents($filename) {
	    if (is_file($filename)) {
	        ob_start();
	        $filled = true;
	        include $filename;
	        $contents = ob_get_contents();
	        ob_end_clean();
	        return $contents;
	    }
	    return false;
	}

?>