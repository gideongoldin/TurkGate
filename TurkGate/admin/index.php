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
	  	    	echo '<p><span class="adjacent">TurkGate is not yet installed.</span><a href="install.php#install" class="adjacent">Install</a></p>';
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
	    <p>
	    	To test the installation, enter a Worker ID and Group Name below and click Test.
	    	Testing simulates a worker accepting a HIT, recording the access request.
	    	Further tests with the same Worker ID and Group Name should result in denied access.
	    </p>
	    <form name="testForm" id="testForm">
	    	<p>
		      <label class="adjacent" for="workerID">Worker ID:</label>
		      <span class="ui-icon ui-icon-help adjacent help" title="Testing actual Mechanical Turk worker IDs will affect workers' access!"></span><br />
	          <input type="text" name="workerID" id="workerID" class="adjacent" />
	          <input type="button" value="Random ID" id="randomID" class="adjacent" /><br />
	        
		      <label class="adjacent" for="groupName">Group Name:</label>
		      <span class="ui-icon ui-icon-help adjacent help" title="Workers will be prevented from participating in multiple studies from the same group."></span>
	          <input type="text" name="groupName" id="groupName" value="test group" />
	        
		      <label class="adjacent" for="surveyURL">Survey URL:</label>
		      <span class="ui-icon ui-icon-help adjacent help" title="Link to your survey. Default value 'test' sends you to a TurkGate test page."></span>
	          <input type="text" name="surveyURL" id="surveyURL" value="test" />
	          
				<label for="HITType" class="adjacent">HIT Type:</label>
				<span class="ui-icon ui-icon-help adjacent help" title="Test as if the HIT was created online or with the Command Line Tools?"></span>
				<select name="HITType" id="HITType">
  					<option value="WebInterface">Web Interface</option>
  					<option value="CLT">Command Line Tools</option>
				</select>
	          
	          	
	          	
				<input type="checkbox" name="previewCheckbox" class="adjacent" id="previewCheckbox" value="preview">
				<label for="previewCheckBox" class="adjacent">Preview Only</label>
				<span class="ui-icon ui-icon-help adjacent help" title="If checked, testing simulates a worker previewing a HIT rather than accepting it. No access request will be recorded."></span>
			</p>
		    <p>
		      	<input type="submit" name="submitTest" id="submitTest" value="Test">
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
		
		function runTest() {
			var workerID = $("#workerID").val();
			var groupName = $("#groupName").val();
			var surveyURL = $("#surveyURL").val();
			var preview = $("#previewCheckbox").attr("checked");
			
	    	switch ($("#HITType").val()) {
	    		case "WebInterface":
	    			testWebHIT(workerID, surveyURL, groupName, preview);
	    			break;
	    		case "CLT":
	    			testCLTHIT(workerID, surveyURL, groupName, preview);
	    			break;
	    		default:
	    			alert("ERROR: Invalid HIT Type.");
	    			return false;
 		   	}
 		   	
 		   	return false;
		}
		
		function testWebHIT(workerID, surveyURL, groupName, preview) {
			var url = "testWebHIT.php?group="+groupName+"&survey="+surveyURL+"&source=ext";
			
			if (!preview) {
				url += "&assignmentId=test&workerId="+workerID
			}

    		window.open(url);
		}
		
		function testCLTHIT(workerID, surveyURL, groupName, preview) {
			var url = "../gateway.php?group="+groupName+"&survey="+surveyURL+"&source=ext";
			
			if (!preview) {
				url += "&assignmentId=test&workerId="+workerID
			}

    		window.open(url);
		}
	
		$(document).ready(function() {
			
			// Setup tooltips
			$(document).tooltip();

			randomizeWorkerID();
			$("#randomID").click(randomizeWorkerID);
			
			$("#testForm").submit(runTest);
		});
	</script>
</body>
</html>

