<?php


function includeWP(){
	$dir = dirname(__FILE__);
	$pos = strpos($dir,"wp-content");
	$base = substr($dir, 0, $pos);

	include "${base}wp-blog-header.php";
}

function fillForm($form_html, $array, $append_class, $readonly, $remove_buttons){

	require_once("lib/simple_html_dom.php");

	$html = str_get_html($form_html);

	$form = $html->find('form',0);
	$form->class .= " filled";

	foreach ($array as $key => $value){

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
					if($readonly){
						$radio->disabled = true;
						$radio->checked = false;
					}
					$radio->class .= " ".$append_class;
					if($radio->value == $value){
						$radio->checked = true;
						$radio->parent()->class .= " ".$append_class;
					}
				}
				continue;
			}

			if($element->type=="checkbox"){
				$checkboxes = $html->find('input[name='.$key.']');
				foreach($checkboxes as $checkbox){
					if($readonly){
						$checkbox->disabled = true;
						$checkbox->checked = false;
					}
					$checkbox->class .= " ".$append_class;
					if(in_array($checkbox->value, $value)){
						$checkbox->checked = true;
						$checkbox->parent()->class .= " ".$append_class;
					}
				}
				continue;
			}

			//echo "Setting the value to $value<br/>";
			if($readonly){
				$element->placeholder = "";
				$element->readonly = true;
			}
			$element->value = $value;
			continue;

		}

		$element = $html->find('select[name='.$key.']',0);
		if($element != null){
			///print_r($element);
			$options = $element->children();
			
			foreach($options as $option){
				if($readonly){
					$option->disabled = true;
					$option->selected = false;
				}
				$option->class .= " ".$append_class;
				if($option->value == $value){
					$option->selected = true;
					$option->parent()->class .= " ".$append_class;
				}
			}
			continue;
			
		}


		$element = $html->find('textarea[name='.$key.']',0);
		if($element != null){
			//echo "Setting the textarea value to $value<br/>";
			$element->innertext = $value;
			if($readonly){
				$element->readonly = true;
			}
			continue;
		}

		//$element->readonly = true;

	}


	// Remove buttons
	if($remove_buttons){

		$buttons = $html->find('input[type=submit]');
		foreach($buttons as $button){
			$button->outertext='';
		}
		$buttons = $html->find('button');
		foreach($buttons as $button){
			$button->outertext='';
		}

	}


	return $html->save();

}

function sanitizeFormFields($form_html, $array){

	require_once("lib/simple_html_dom.php");

	$html = str_get_html($form_html);

	foreach ($array as $key => $value){

		if(is_array($value)){
			$key .= "[]";
		}

		$array[$key] = mysql_real_escape_string($value);

		$element = $html->find('input[name='.$key.']',0);
		if($element != null){
			continue;
		}
		$element = $html->find('textarea[name='.$key.']',0);
		if($element != null){
			continue;
		}
		$element = $html->find('select[name='.$key.']',0);
		if($element != null){
			continue;
		}

		unset($array[$key]);

	}

	return $array;
}

function serializeArray($array){
	$serialized = "";

	foreach($array as $key=>$value){
		$value = str_replace(array("|","=",">"), array("&#124;","&#61;","&#62;"), $value);
		
		$serialized .= "$key =>$value|";
	}

	return $serialized;
}

function deserializeArray($string){
	$parts = explode("|", $string);
	$deserialized = array();
	foreach($parts as $part){

		$key_value = explode("=>", $part);

		if(count($key_value)==2){
			$key = trim($key_value[0]);
			$deserialized["$key"] = trim($key_value[1]);
		}
	}

	return $deserialized;
}

function endsWith($haystack, $needle){
	$length = strlen($needle);
	if ($length == 0) {
		return true;
	}

	return (substr($haystack, - $length) === $needle);
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
