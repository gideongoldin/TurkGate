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

// Get TurkGate's database configuration
$installed = @include('../config.php');

// If the form was submitted, test survey access
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	// only add an assignment ID if testing as an accepted HIT, not a preview
	$acceptString = isset($_POST['submitTestAccept']) ? 'assignmentId=test&' : '';
	
    $workerID = urlencode($_POST['workerID']);
    $groupName = urlencode($_POST['groupName']);
	$surveyURL = urlencode($_POST['surveyURL']);
	$source = urlencode($_POST['source']);
	
    header("Location: ../gateway.php?".$acceptString."workerId=$workerID&group=$groupName&survey=$surveyURL&source=$source");
	exit();
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>TurkGate - Administration</title>
	<meta name="description" content="TurkGate tools for the administrator.">
	<meta name="author" content="">
	
	<!-- Add the imports -->
	<?php 
    $basePath = '../';
	    require_once($basePath . 'includes/imports.php'); 
	?>
	
</head>

<body id="admin">
	
  <div class="container"> <!-- Container -->
	
	<!-- Add the header -->
	<?php 
    $title = 'Administration';
	    require_once($basePath . 'includes/header.php'); 
	?>
			
	<div class="sixteen columns clearfix" style="border-top: 1px solid #ccc; padding-top:10px;"> <!-- sixteen columns clearfix -->
	  	<h3>Installation</h3>
	  	<?php
	  	    if ($installed) {
	  	        echo '<p><span class="adjacent">TurkGate is installed.</span><a href="install.php#uninstall" class="adjacent">Uninstall</a></p>';
	  	    } else {
	  	        echo '<p>TurkGate is not yet installed.';
				echo '<br /><a href="install.php#install">Install TurkGate</a></p>'; 
	  	    }
	    ?>
	</div>
	<div class="sixteen columns" style="border-top: 1px solid #ccc; padding-top:10px;">	
	    <h3>Testing</h3>
	    <?php
	        if (!$installed) {
	        	echo '<br /><strong><p class="danger" style="text-align:center;">NOTE: Testing is unlikely to work as TurkGate is not yet installed!</p></strong>';
	        }
		?>
	    <p>To test the installation, enter a workerId and group name below and click Test.</p>
	    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	    	<p>
		      <label for="workerID">Worker ID:</label>
	          <input type="text" name="workerID" id="workerID" class="adjacent" />
	          <input type="button" value="Random ID" id="randomID" class="adjacent" />
	        
		      <label for="groupName">Group:</label>
	          <input type="text" name="groupName" value="test group" />
	        
		      <label for="surveyURL">Survey URL:</label>
	          <input class="adjacent" type="text" name="surveyURL" value="test" />
	          <span class="comment adjacent">(Default value 'test' sends you to a TurkGate test page.)</span>
	        </p>
		    <p>
		      	You must also specify what kind of HIT you want to test. This changes 
		      	whether TurkGate displays a link to the survey or redirects to it.
		    </p>
		    <p>
		        <input type="radio" name="source" value="js">Web Interface HIT</input>
				<br />
				<input type="radio" name="source" value="ext" checked>Command Line Tools HIT</input> 
		    </p>
		    <p>
		      	<input type="submit" name="submitTestPreview" value="Test Preview HIT">
		      	<input type="submit" name="submitTestAccept" value="Test Accept HIT">
		      	<span class="comment">(Testing, like normal access requests, adds an entry to the database)</span>
		    </p>
	    </form>
	</div>
	    
	<!-- Import the footer -->
	<?php require_once($basePath . 'includes/footer.php'); ?>
	
	</div> <!-- Container -->
	
	<script>
		function randomizeWorkerID() {
			var randomID = "testID_" + Math.floor(Math.random()*Math.pow(10, 10)).toString(16);
			$("#workerID").val(randomID);
		}
	
		$(document).ready(function() {
			randomizeWorkerID();
			$("#randomID").click(randomizeWorkerID);
		});
	</script>
</body>
</html>

