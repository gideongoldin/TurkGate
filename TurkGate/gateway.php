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

session_start();

if (!include_once('config.php')) {
    die('A configuration error occurred. ' 
      . 'Please report this error to the HIT requester.');
}

require_once 'lib/databaseobject.php';
require_once 'lib/tempstorage.php';

$accessController = new DatabaseObject();


$workerId = null;
if (!empty($_GET['workerId'])) {
	$workerId = htmlspecialchars($_GET['workerId']);
} else if (!empty($_POST['checkid_id'])) {
	$workerId = htmlspecialchars($_POST['checkid_id']);
}
	

$groupName = urldecode($_GET['group']);

$isExternalHIT = empty($_GET['source']) || $_GET['source'] == 'ext';
$isPreview = empty($_GET['assignmentId']) || 
	$_GET['assignmentId'] == 'ASSIGNMENT_ID_NOT_AVAILABLE';

if ($isPreview) {
	
	if ($workerId == null) { ?>
	  <p>Sorry, this study cannot be previewed. Please accept the HIT in order to view it.</p>
	  <p>
	  	You will only be able to view the survey if you have not accepted  
	    any other HITs related in the '<?php echo $groupName; ?>' group. You can check your eligibility below (opens in a new window).
      </p>
	  <p>
	  	<form id='checkid_form' method='POST' target='_blank' action='gateway.php?group=<?php echo urlencode($groupName); ?>'> 
	  	  Mechanical Turk Worker ID: 
	  	  <input type="text" name="checkid_id" id="checkid_id" size="30" required="">
		  <input type="submit" name="checkid_submit" id="checkid_submit" value="Check Eligibility">
	  	</form>
	  </p>
	  <p>
	  	If you have disabled browser cookies or if you restart your 
	    browser after accepting the HIT, it might not work properly. Be sure  
	    to have any plug-ins required by the HIT installed before accepting it.
	  </p>
<?php
	} else {
	    $accessAllowed = $accessController->checkAccess($workerId, $groupName, false);
	
	    // Block previews of the HIT, but warn about possible exclusion based on
	    // group name
		if ($accessAllowed) {
	        echo "<p>You have not done any surveys in the $groupName group.</p>";
	        echo '<p>Once you accept the HIT, you will be able to access the survey.</p>';
		} else {
	        echo "<p>You have already accessed a survey in the $groupName group.</p>";
	        echo '<p>Do NOT accept the HIT, because you will NOT be able to access the survey.</p>';
		}
		
	}
} else {
    // This worker has accepted the HIT, so we can continue
    $assignId = htmlspecialchars($_GET['assignmentId']);
	

	// parse survey identifier, first word is a code for finding the base URL, 
	// second word is a survey-specific id
	$surveyURL = urldecode($_GET['survey']);
	
	
	if (strlen($surveyURL) == 0 || $surveyURL == 'test') {
	    // If no survey URL was submitted, or if 'test' was entered, insert the
	    // testDestination.php page.
	    $surveyURL = 'admin/testDestination.php';
	}
		
	// This is the form for submitting the completion codes and comments for an
	// external HIT.
	// Not needed if this page was reached through a link from a regular HIT.
	$htmlForm = "<form id='mturk_form' method='POST' " 
	  . "action='http://www.mturk.com/mturk/externalSubmit'>" 
	  . "<input type='hidden' id='assignmentId' name='assignmentId' " 
	  . "value='$assignId'>";
	$htmlCompletionCode = '<p>Completion code: <input type="text" ' 
	  . 'name="completion_code" id="completion_code" size="60"></p>';
	$htmlSubmitButton = '<input id="submit_button" type="submit" '  
	  . 'name="submit_button" value="Submit" onclick="allowExit()">';
	$htmlComments = '<p>Comments (optional): <br><textarea cols="60" rows="4" '  
	  . 'id="comments" name="comments"></textarea></p>';
	

    //If access isn't granted, this worker has already done a survey in the group and will
    // be blocked from reaching the survey.
    if ($accessController->checkAccess($workerId, $groupName, true, $surveyURL)) {        // ACCESS GRANTED
	
		$store = new tempStorage();
		  
		$store->store('Worker_ID', $workerId, true);
		$store->store('Group_Name', $groupName, true);

        if ($_GET['source'] == 'js') {
            // if coming from javascript, simply redirect because the HIT
            // response fields are on another page
            header('Location: ' . $surveyURL);
        } else {
            // if an external HIT, present a link and the form for submitting the
            // completion code and comments
            // Warn the worker before they leave the page that the link to the
            // survey will be lost.
            echo '<html>';
            echo '<head>';
            echo '<script>
					function exit() {
						return "WARNING: You will not be able to access the ' 
						. 'survey after leaving or refreshing this page!";
					}
					
					window.onbeforeunload = exit;
					
					function allowExit() {
						window.onbeforeunload = null;
					}									
				</script>';
            echo '</head>';
            echo '<body>';

            echo '<p>The link below will open the study in a new window.</p>';
            echo '<p>At the end of the survey you will receive a confirmation ' 
              . 'code. Submit that confirmation code in the text entry box ' 
              . 'below in order to recieve credit for this HIT.</p>' 
              . '<p>Thank you.</p>';
            echo '<p><a name="surveyLink" id="surveyLink" target="_blank" ' 
              . 'href="' . $surveyURL . '">Click here to open the study.</a>' 
              . '</p>&nbsp;';

            echo $htmlForm;
            echo $htmlCompletionCode;
            echo $htmlComments;
            echo $htmlSubmitButton;
            echo '</form>';

            echo '</body></html>';
        }    	
    } else {
    	// ACCESS DENIED
        // On the chance that they completed the survey but then closed the browser,
        // we still give them the option to enter a completion code.
    	echo '<html><body>';
        // If this page was reached from a link and the HIT page is accessible in
        // another window
        if ($_GET['source'] == 'js') {
            echo 'If you completed the survey for this HIT, enter the ' 
              . 'completion code in the HIT page on Mechanical Turk.<br>';
        } else {
            // If this is an external hit, the submission form and explanation
            // for being blocked are presented in the HIT page
            echo $htmlForm;
            echo 'If you accidentally left this page after accepting the HIT, ' 
              . 'but have completed the survey you can enter the completion ' 
              . 'code here.';
            echo $htmlCompletionCode;
            echo $htmlComments;
            echo $htmlSubmitButton;
            echo '</form>';
        }

        echo 'Otherwise, you may have already completed another survey in ' 
          . 'this group and cannot complete this one. Please return the HIT.<br>';
        echo '</body></html>';
    }

    $accessController->close();
}

?>