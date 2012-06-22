<html><body>
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
<!-- This page can be used to test whether SurveyForm.php sends you where you intend and what variables are set. In order to reach this page through SurveyForm.php, add it to the SurveySites database along with an identifier (e.g. "test"). If you then go to SurveyForm.php with the parameter "survey=test 1", it will send you to this page. The "1" is arbitrary but required. SurveyForm expects a specific survey identifier but this page doesn't care what it is. -->
	<p>You have reached your destination. Congratulations.</p>
	<p>Here are the parameters you sent:</p>
	<ul>
		<?php		
		session_start();
		
		// print all URL parameters
		foreach ($_GET as $key => $value) {
			echo "<li> URL: $key = $value </li>";
		}
		
		// print all cookies
		foreach ($_COOKIE as $key => $value) {
			echo "<li> COOKIE: $key = $value </li>";
		}
		
		// print all session variables
		foreach ($_SESSION as $key => $value) {
			echo "<li> SESSION: $key = $value </li>";
		}
		?>
	</ul>
    <!-- You can add a link to the completion code here to test it as well. 
    <a href="http://your.completion.code/completed.php?var=42">Completion code</a> -->
</body></html>
