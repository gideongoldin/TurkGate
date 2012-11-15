<?php 
    session_start(); 

    if (!include('../config.php')) {
        die('A configuration error occurred. ' 
          . 'Please report this error to the HIT requester.');
    }
	
    $encryptionKey = constant('KEY');
	  
	$workerId = "";
	$groupName = "";
	
	if(isset($_COOKIE['Worker_ID']) && isset($_COOKIE['Group_Name'])) {
	    $workerId = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryptionKey), base64_decode($_COOKIE['Worker_ID']), MCRYPT_MODE_CBC, md5(md5($encryptionKey))), "\0");
	    $groupName = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryptionKey), base64_decode($_COOKIE['Group_Name']), MCRYPT_MODE_CBC, md5(md5($encryptionKey))), "\0");
	}
  

	// Clear previously set cookies
	// Set the expiration date to the past
	setcookie("Worker_ID", "", time()-3600, '/');
	setcookie("Group_Name", "", time()-3600, '/');
    
?>

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
<!-- Import the header -->
<?php 
    $title = 'Completion Code';
    $description = 'Generates a completion code that verifies a worker completed the survey.';
    $basePath = '../';
    require_once($basePath . 'includes/header.php'); 
?>
<script>
  function exit() {
    return "Please verify that you have saved the code on this page before leaving!";
  }

  window.onbeforeunload = exit;
</script>
<?php
    // Add the Worker ID and the Group name to the input string
    $inputString = "w[$workerId]g[$groupName]";

    // Add any key-value pairs from the GET array to the input string
    foreach ($_GET as $key => $value) {
        $inputString .= $key[0]."[$value]";
    }

    // Construct the completion code
    $completionCode = $inputString . ':' . sha1($inputString . $encryptionKey);
?>
  <div class="sixteen columns">
    <header><h1>Thank you!</h1></header>
  </div>
  <div class="sixteen columns clearfix" style="border-top: 1px solid #DDD; padding-top:10px;"> <!-- sixteen columns clearfix -->
    <p>Please enter the code below into Mechanical Turk:</p>
    <p id="code"><?php echo $completionCode ?></p>
  </div>

<?php require_once('../includes/footer.php'); ?>
