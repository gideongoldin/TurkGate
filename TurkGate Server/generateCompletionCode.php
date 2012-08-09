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

	=============================================================================
	
	Upon proper completion of a survey, workers may obtain a completion code through TurkGate. To enable this, simply redirect your user to the PHP file generateCompletionCode.php on your external server. Certain survey software (e.g., LimeSurvey) offer automatic redirection.

	The script underlying this page will automatically generate a completion code. This code contains a worker's Mechanical Turk Worker ID, the name of the Group the survey belonged to, and a set of optional, custom-defined key-value pairs. The code also includes a hashing of the aforementioned elements with a private, user-defined salt.

	Both the Worker ID and the Group name are retrieved from previously set cookies (automatically), while the set of key-value pairs are retrieved from PHP's GET variable. For most purposes, you will not need to include custom key-value pairs. If you would like to, however, include something like a worker's browser information, you could do so by simply appending it to the URL in your redirection: http://yourexternalserver.com/youroptionalsurveypath/generateCompletionCode.php?version=safariForiOS

	The format of generated completion codes is as follows:

	w[Worker ID]g[Group name]v[value]:hashingOfAforementionedElementsWithSalt

	The first half of the code (i.e., that preceding the colon), is a collection of key-value pairs, where the letters outside of the brackets (e.g., w) indicate variables and the strings inside (e.g., Worker ID) represent their values. As stated above, w[Worker ID]g[Group name] are generated automatically, while v[value] is optional and retrieved from the URL.

	Any number of key-value pairs may be specified. Within these pairs, v is extracted as the first character in the GET array's variable (e.g., version), while value will comprise the contents of this variable (e.g., safariForiOS).

	The second half of the code (i.e., that following the colon), is a hashing of the first half with a salt. Be sure to substitute your own salt value in the PHP file in order to ensure proper security.
-->
<?php session_start(); ?>
<!doctype html>  
	<head>	
		<title>TurkGate</title>
		<script>
			//<![CDATA[
			function exit() {
				return "Please verify that you have saved the code on this page before leaving!";
			}
			window.onbeforeunload = exit;
			//]]>
		</script>
		<style type="text/css">
			body { margin: 50px auto; text-align: center; }
			#code { color: yellow; background: black; font-family: monospace; padding: 10px 0; }
			footer, a { color: gray; }
		</style>
	</head>
	<body>
		<?php	
			// Add the Worker ID and the Group name to the input string
			$inputString = 'w[' . $_COOKIE['Worker_ID'] . ']g[' . $_COOKIE['Group_Name'] . ']';
		
			// Add any key-value pairs from the GET array to the input string.
			foreach($_GET as $key => $value) {
				$inputString .= $key[0] . '[' . $value . ']';
			}
			
			// Prepare salt (replace 'shaker' with your own key)
			// NOTE: This value must match that defined in verifyCompletionCode.php!
			$salt = 'shaker';
			
			// Construct the completion code
			$completionCode = $inputString . ':' . sha1($inputString . $salt);
			
			// Display code to the user
			echo '<header><h1>Thank you!</h1></header>';
			echo '<p>Please enter the code below into Mechanical Turk:</p>';
			echo '<p id="code">' . $completionCode . '</p>';
			echo '<footer><h6>Powered by <a href="https://github.com/gideongoldin/TurkGate" title="TurkGate" target="_blank">TurkGate</a></h6></footer>';
		?>
	</body>
</html>