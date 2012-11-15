<?php
/*

	Copyright 2012 Adam Darlow and Gideon Goldin

	Licensed under the Apache License, Version 2.0 (the "License");
	you may not use this file except in compliance with the License.
	You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

	Unless required by applicable law or agreed to in writing, software
	distributed under the License is distributed on an "AS IS" BASIS,
	WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
	See the License for the specific language governing permissions and
	limitations under the License.

*/
	require 'lib/fixhttp.lib.php';

	if(isset($_POST['downloadCLTFile'])) {
	
		// Forces browser to download instead of open files
		header("Content-Type: application/octet-stream");
		
		// Required for base URL
		$installed = @include('config.php');
		
		//All of the variables in the files that need to be substitute
		$substitutions = array( '[[[TurkGate URL]]]' => constant('BASE_URL'));
		$substitutions['[[[Survey URL]]]'] = fix_http($_POST['externalSurveyURL']);
		$substitutions['[[[Group Name]]]'] = $_POST['groupName'];	
										
		// File name pulled from submit button values
		$fileName = $_POST['downloadCLTFile'];
		$file = 'resources/CLTHIT/' . $fileName;
		
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
		
		// Perform the specified substitutions
		// NOTE: Could fail with files larger than buffer size!
		foreach ($substitutions as $original => $new) {
		    $text = str_replace($original, $new, $text);
		}

		echo $text;
		
		fclose($fp);
		exit;
	}

	// Create a string for the HTML code
	$webTemplateString = "";
	
	// Get the form values
	$externalSurveyURL = isset($_POST['externalSurveyURL']) ? fix_http($_POST['externalSurveyURL']) : "";
	$groupName = isset($_POST['groupName']) ? $_POST['groupName'] : "";	

	// Check if TurkGate is installed
	$installed = @include 'config.php';
	
	if(!$installed) {
		echo '<p>TurkGate does not appear to be installed. See your administrator.</p><p><a href="admin/index.php">Admin home</a></p>';
		echo '<h5>Powered by <a href=http://gideongoldin.github.com/TurkGate/">TurkGate</a></h5>';
		exit;
	} else {
		if(isset($_POST['generateHTMLCode'])) {
				// Modify the web template
			// First read the entire file
			$webTemplateString = file_get_contents('resources/WebHIT/webTemplate.html');

			// Make the necessary changes
			$webTemplateString = str_replace('[[[Survey URL]]]', fix_http($_POST['externalSurveyURL']), $webTemplateString);
			$webTemplateString = str_replace('[[[Group Name]]]', $_POST['groupName'], $webTemplateString);
			$webTemplateString = str_replace('[[[TurkGate URL]]]', constant('BASE_URL'), $webTemplateString);
			$copyright = "<!-- Copyright (c) 2012 Adam Darlow and Gideon Goldin. For more info, see http://gideongoldin.github.com/TurkGate/ -->\n";
			$webTemplateString = preg_replace('/<!--[^>]*-->/', $copyright, $webTemplateString, 1);
		}
	}	
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>TurkGate</title>
	<link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
	<div class="wrapper">
		<header>
	  		<a href="index.php"><h1>TurkGate</h1></a>
		</header>
  	
		<hr />

		<p>
			From here you may generate the HTML code for your Web Interface HIT, or download files for use with the Command Line Tool.
		</p>
	
		<h2>Generate a HIT</h1>
	
		<form method="post" action="index.php" id="hitGenerationForm">
			<fieldset>
			<legend>HIT Generation</legend>
				<p>
					Please specify a survey URL and group name:
				</p>
	
				<p>
					<input type="text" name="externalSurveyURL" value=<?php echo "'$externalSurveyURL'"; ?> size="40" placeholder="http://surveysite.com/surveyid" autofocus required>
					<label for="externalSurveyURL">External Survey URL*</label>
				</p>

				<p>
					<input type="text" name="groupName" value=<?php echo "'$groupName'"; ?> size="40" placeholder="Test group name" required>
					<label for="groupName">Group Name*</label>
				</p>

				<h4>For the Mechanical Turk Web Interface: </h4>
		
				<p>Generate the HTML code to paste into your HIT using the values specified above.
				Full instructions are on the <a href="http://gideongoldin.github.com/TurkGate/" target="blank">TurkGate Wiki</a>.</p>
	
				<p>
					<input type="submit" name="generateHTMLCode" id="generateHTMLCode" value="Generate HTML code">
				</p>

				<?php
					// Generate a text area with the HTML code
					if(strlen($webTemplateString) > 0) {
						echo '<p>Copy and paste the code below into the source code for your HIT:';
						echo '<p><textarea rows="15" cols="80" id="generatedHTMLCode">';
						echo $webTemplateString;
						echo '</textarea></p>';
					}
				?>
		
				<h4>For the Mechanical Turk Command Line Tools: </h4>
		
				<p>Download the files for creating your HIT using the values specified above.
				Full instructions are on the <a href="https://github.com/gideongoldin/TurkGate/wiki/Command-Line-Tools" target="blank">TurkGate Wiki</a>.</p>
		
				<p>
					Download: 
					<input type="submit" name="downloadCLTFile" value="survey.input">
					<input type="submit" name="downloadCLTFile" value="survey.properties">
					<input type="submit" name="downloadCLTFile" value="survey.question">
				</p>
			</fieldset>
		</form>
	
		<hr />
	
		<h4>Completion Codes</h4>

		<p>			
			To automatically generate completion codes at the end of your surveys, 
			redirect your workers to the following URL: <a href="#"><?php echo constant('BASE_URL'); ?>/codes/generate.php</a>.
		</p>
	
		<p>
			Click <a href="codes/verify.php">here</a> to verify completion codes.
		</p>
	
		<p>
			Visit the TurkGate Wiki pages on <a href="https://github.com/gideongoldin/TurkGate/wiki/Completion-Code-Generation" target="blank">Completion code generation</a> and <a href="https://github.com/gideongoldin/TurkGate/wiki/Completion-Code-Verification" target="blank">Completion code verification</a> for more information.
		</p>
	
		<hr />
	
		<footer>
		    <h5>
		      Powered by <a href='http://gideongoldin.github.com/TurkGate/'>TurkGate</a>. <span style="float:right;">Copyright &copy; Adam Darlow & Gideon Goldin.</span>
		    </h5>
			
		</footer>
	</div>
  </body>
</html>
