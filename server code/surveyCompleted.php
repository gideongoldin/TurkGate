<?php session_start();
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

/*************************************
 * Completion code page
 *
 * Outputs a string by combining the Mechanical Turk worker ID, key-value pairs in the GET variable,
 * a private salt, and a hashing of these elements together. The key-value pairs may be things like
 * the survey name, a database entry for this user, or whatever else is needed.
 *************************************/
 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Congratulations!</title>

<script>
// If the user requests to leave the page, provide an alert box for confirmation.
function closeIt() {
  return "Please verify that you have saved the code on this page before leaving!";
}
window.onbeforeunload = closeIt;
</script>

</head>

<body>
<?php
	// Below we will construct the unique completion code.
	// First, gather the Worker ID & group name from the cookie variable and add it to a new input string
	// Each value in the input string comprises a one character code (e.g., 'w', 'g') and it's
	// corresponding value (e.g., the worker id, group name).
	$inputCode = 'w[' . $_COOKIE['Worker_ID'] . ']g[' . $_COOKIE['Group_Name'] . ']'; // 'w' stands for 'worker id'

	// Next, get the key-value pairs (if any) from the GET array and add them to the input string.
	// Here, we take the first character from the key field, and then concatenate to it the 
	// corresponding value.
	foreach($_GET as $key => $value) {
		$inputCode .= $key[0] . '[' . $value . ']';
	}
	
	// Now, we declare a secret salt to be added to the input string. This will improve the quality
	// of our hashing in the next step. The value for $salt must be changed on each installation
	// of this software!
	$salt = 'ines';
	
	// Finally, we determine the output code by combining the input code and the salt with a 
	// hasing of the input code and the salt.
	$outputCode = $inputCode . ':' . md5($inputCode . $salt);
	
	// Display the output code to the user.
	echo '<p style="text-align:center; font-weight:bold;">Thank you!</p>';
	echo '<p style="text-align:center;">Please enter the code below into the Mechanical Turk HIT page to receive credit for your participation:</p>';
	echo '<p style="font-size:20px; color:yellow; background:black; font-family:Courier, monospace; font-weight:bold; text-align:center; padding:10px 0;">' . $outputCode . '</p>';
?>
</body>
</html>