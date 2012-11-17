<?php
		// Forces browser to download instead of open files
		header("Content-Type: application/octet-stream");
										
		// File name pulled from submit button values
		$fileName = urldecode($_GET['file']);
		$file = '../resources/CLTHIT/' . $fileName;
		
		$substitutions = array();
		foreach ($_GET as $key => $value) {
			if (strpos($key, 'sub') === 0) {
				$decoded = urldecode($value);
				$splitIndex = strpos($decoded, ']]]') + 3;
				$substitutions[substr($decoded, 0, $splitIndex)] = substr($decoded, $splitIndex);
			}
		}
		
		header("Content-Disposition: attachment; filename=" . $fileName);   
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Description: File Transfer");            
		header("Content-Length: " . filesize($file));
		//flush(); // this doesn't really matter.
		$fp = fopen($file, "r");

		// NOTE: The buffer limit should be noted here
		// to verify that the entire file is read!
		$text = stream_get_contents($fp);
		
		// All variables other than 'file' refer to substitutions
		// NOTE: Could fail with files larger than buffer size!
		foreach ($substitutions as $original => $new) {
			$text = str_replace(urldecode($original), urldecode($new), $text);
			$text = $text . "\n" . urldecode($original) . " => " . urldecode($new);
		}

		echo $text;
		
		fclose($fp);
		exit;
?>