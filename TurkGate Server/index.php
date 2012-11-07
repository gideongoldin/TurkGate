<!--

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

-->

<?php

	// Create a string for the HTML code
	$webTemplateString = "";
	$externalSurveyURL = isset($_POST['externalSurveyURL']) ? $_POST['externalSurveyURL'] : "";
	$groupName = isset($_POST['groupName']) ? $_POST['groupName'] : "";	

	$installed = @include('turkGateConfig.php');
	
	// Get TurkGate's configuration
	if(!installed) {
		echo '<p>TurkGate does not appear to be install. See your administrator.</p><p>Go <a href="index.php">back</a>.</p>';
	} else {	
		// Check if the uninstall (vs. install) form was submitted
		if(isset($_POST['submit'])) {

			// Modify the web template and offer a download link
			// First read the entire file
			$webTemplateString = file_get_contents('../Web Interface/HIT Template.html');

			// Open the template
			$fp = fopen('../Web Interface/HIT TemplateTest.html','w');

			// Make the necessary changes
			$webTemplateString = str_replace('surveyURL = "test";', 'surveyURL = "' . $_POST['externalSurveyURL'] .  '";', $webTemplateString);
			$webTemplateString = str_replace('group = "testing js";', 'group = "' . $_POST['groupName'] .  '";', $webTemplateString);
			$webTemplateString = str_replace('turkGateURL = "http://YOUR.INSTALLATION.EDU";', 'turkGateURL = "' . constant('BASE_URL') .  '";', $webTemplateString);
			$copyright = "<!-- Copyright (c) 2012 Adam Darlow and Gideon Goldin. For more info, see http://gideongoldin.github.com/TurkGate/ -->\n";
			$webTemplateString = preg_replace('/<!--[^>]*-->/', $copyright, $webTemplateString, 1);			
		}
	}	
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>TurkGate</title>
  </head>
  <body>
  	<h1>TurkGate</h1>
  	
	<p>
		From here you may generate the HTML code for your Mechanical Turk HIT, or download template files for use with the Command Line Tool.
	</p>
	
	<h2>Mechanical Turk Web Interface</h2>
	
	<form method="post" action="index.php">
	
		<p>
			Please fill out the form below, and press generate.
		</p>
	
		<p>
			<label for="externalSurveyURL">External Survey URL:</label>
			<input type="text" name="externalSurveyURL" value=<?php echo "'$externalSurveyURL'"; ?> autofocus="autofocus"> <!-- required="required" -->
		
		</p>

		<p>
			<label for="groupName">Group Name:</label>
			<input type="text" name="groupName" value=<?php echo "'$groupName'"; ?> autofocus="autofocus"> <!-- required="required" -->
		</p>
	
		<input type="submit" name="submit" value="Generate HTML code">

		<?php
			if(strlen($webTemplateString) > 0) {
				echo '<p>Copy and paste the code below into the source code for your HIT:';
				echo '<p><textarea rows="25" cols="80">';
				echo $webTemplateString;
				echo '</textarea></p>';
			}
		?>

	</form>
	
	<h2>Command Line Tools Template Files</h2>
	
	<ul>
		<li><a href="downloadManager.php?file=../Command Line Tools/survey.input">Download survey.input</a></li>
		<li><a href="downloadManager.php?file=../Command Line Tools/survey.properties">Download survey.properties</a></li>
		<li><a href="downloadManager.php?file=../Command Line Tools/survey.question">Download survey.question</a></li>
	</ul>
	
    <h5>
      Powered by <a href='https://github.com/gideongoldin/TurkGate'>TurkGate</a>
    </h5>
  </body>
</html>
