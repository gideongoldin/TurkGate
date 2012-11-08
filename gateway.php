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

if (!include('config.php')) {
    die('A configuration error occurred. ' 
      . 'Please report this error to the HIT requester.');
}

$groupName = urldecode($_GET['group']);

// parse survey identifier, first word is a code for finding the base URL, 
// second word is a survey-specific id
$surveyURL = urldecode($_GET['survey']);
if (strlen($surveyURL) == 0 || $surveyURL == 'test') {
    // If no survey URL was submitted, or if 'test' was entered, insert the
    // testDestination.php page.
    $surveyURL = 'admin/testDestination.php';
}

// We use assignmentId to identify whether the worker is merely previewing the
// HIT or has accepted it.
if ($_GET['assignmentId'] == 'ASSIGNMENT_ID_NOT_AVAILABLE' 
    || empty($_GET['assignmentId'])) {

    // Block previews of the HIT, but warn about possible exclusion based on
    // group name

    echo '<p>Sorry, this study cannot be previewed. ' 
      . 'Please accept the HIT in order to view it.</p>';
    echo "<p>You won't be able to complete the HIT if you have already done " 
      . "other HITs related to $groupName. If this is the case, do not " 
      . "accept the HIT.</p>";
    echo '<p>If you have disabled browser cookies or if you restart your ' 
      . 'browser after accepting the HIT, it might not work properly. Be sure ' 
      . 'to have any plug-ins required by the HIT installed before accepting ' 
      . 'it.</p>';
} else {
    // This worker has accepted the HIT, so we can continue

    $workerId = htmlspecialchars($_GET['workerId']);
    $assignId = htmlspecialchars($_GET['assignmentId']);

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

    // parameters for accessing the database
    $dbUsername = constant('DATABASE_USERNAME');
    $dbPassword = constant('DATABASE_PASSWORD');
    $dbName = constant('DATABASE_NAME');
    $dbHost = constant('DATABASE_HOST');

    // connect to the database
    $con = mysql_connect($dbHost, $dbUsername, $dbPassword) 
           or die('There was an error connecting to the database. ' . 
             'Please contact the requester to notify them of the error.');

    mysql_select_db($dbName) 
      or die('There was an error selecting the database. ' . 
        'Please contact the requester to notify them of the error.');

    // Look for entries with the same workerId and groupName.
    $query = "SELECT * FROM SurveyRequest WHERE " 
      . "SurveyRequest.workerID='$workerId' " 
      . "AND SurveyRequest.groupName='$groupName';";
	  
    $result = mysql_query($query) 
              or die('There was an error retreiving access info. Please ' 
                . 'contact the requester to notify them of the error.');

    //If one exists, this worker has already done a survey in the group and will
    // be blocked from reaching the survey.
    // On the chance that they completed the survey but then closed the browser,
    // we still give them the option to enter a completion code.
    if (mysql_numrows($result) > 0) {
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
    } else {
        // ACCESS GRANTED

        // save the worker ID and group name for later creating the completion code
        $key = constant('KEY');
		$encryptedWorkerId = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 
		  md5($key), $workerId, MCRYPT_MODE_CBC, md5(md5($key))));
		$encryptedGroupName = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 
		  md5($key), $groupName, MCRYPT_MODE_CBC, md5(md5($key))));
        $_SESSION['Worker_ID'] = $encryptedWorkerId;
        $_SESSION['Group_Name'] = $encryptedGroupName;
        setcookie('Worker_ID', $encryptedWorkerId, time() + (24 * 60 * 60), '/');
        setcookie('Group_Name', $encryptedGroupName, time() + (24 * 60 * 60), '/');

        // Add the access to the database to block future access
        $query = "INSERT INTO SurveyRequest " 
          . "(SurveyRequest.workerID, SurveyRequest.groupName, SurveyRequest.URL) " 
          . "VALUES ('$workerId', '$groupName', '$surveyURL');";
		  
        $result = mysql_query($query) 
                  or die('There was an error saving to the database. Please ' 
                    . 'contact the requester to notify them of the error.');

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
    }

    mysql_close($con);
}
