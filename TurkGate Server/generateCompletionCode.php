<?php session_start(); ?>

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

<!doctype html>
<head>
  <title>TurkGate</title>
  <script>
    function exit() {
      return "Please verify that you have saved the code on this page before leaving!";
    }

    window.onbeforeunload = exit;
  </script>
  <style type="text/css">
	body {
		margin: 50px auto;
		text-align: center;
	}
	#code {
		color: yellow;
		background: black;
		font-family: monospace;
		padding: 10px 0;
	}
	footer, a {
		color: gray;
	}
  </style>
</head>
<body>
  <?php
      // Add the Worker ID and the Group name to the input string
      $inputString = 'w[' . $_COOKIE['Worker_ID'] . ']g[' 
                     . $_COOKIE['Group_Name'] . ']';

      // Add any key-value pairs from the GET array to the input string.
      foreach ($_GET as $key => $value) {
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
      echo "<footer><h5>&copy; 2012 <a href='https://github.com/gideongoldin/TurkGate' target='blank'>TurkGate</a></h5></footer>";

  ?>
</body>
</html>