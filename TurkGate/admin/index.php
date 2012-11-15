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
if (isset($_POST['submit'])) {
    $workerID = urlencode($_POST['workerID']);
    $groupName = urlencode($_POST['groupName']);
	$surveyURL = urlencode($_POST['surveyURL']);
	$source = urlencode($_POST['source']);

    header("Location: ../gateway.php?assignmentId=test&workerId=$workerID&group=$groupName&survey=$surveyURL&source=$source");
	exit();
}
?>


<!-- Import the header -->
<?php 
    $title = 'TurkGate Central';
    $description = 'TurkGate tools for researchers.';
    $basePath = '../';
    require_once($basePath . 'includes/header.php'); 
?>
  <body>
  	<header><h1>TurkGate Administration Page</h1></header>
  	<h2>Installation</h2>
  	<?php
  	    if ($installed) {
  	        echo '<p>TurkGate has already been installed!</p>';
			echo '<p><a href="install.php">Uninstall TurkGate</a></p>';
  	    } else {
  	        echo '<p>TurkGate is not yet installed.</p>';
			echo '<p><a href="install.php">Install TurkGate</a></p>'; 
  	    }
    ?>
    <br>
    <h2>Testing</h2>
    <?php
        if (!$installed) {
        	echo '<h3>***** Testing is unlikely to work without installing ' 
        	    . 'TurkGate first. *****</h3>';
        }
	?>
    <p>To test the installation, enter a workerId and group name below and click Test.</p>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <table>
        <tr>
          <td> Worker Id: </td>
          <td>
          <input type="text" name="workerID" value="1">
          </td>
        </tr>
        <tr>
          <td> Group: </td>
          <td>
          <input type="text" name="groupName" value="test group">
          </td>
        </tr>
        <tr>
          <td> Survey URL: </td>
          <td>
            <input type="text" name="surveyURL" value="test">
          </td>
          <td><i>(The default sends you to a TurkGate test page.)</i></td>
        </tr>
      </table>
      <p>
      	You can also specify what kind of HIT you want to test. This changes 
      	whether TurkGate displays a link to the survey or redirects to it.
      </p>
      <p>
        <input type="radio" name="source" value="ext" checked>Command Line Tools HIT</input>
      </p>
      <p>
      	<input type="radio" name="source" value="js">Web Interface HIT</input>
      </p>
      <p>
      	<input type="submit" name="submit" value="Test">
      </p>
    </form>
    
<!-- Import the footer -->
<?php require_once($basePath . 'includes/footer.php'); ?>
