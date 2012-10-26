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
/*
  This page, together with the necessary databases, enables an Amazon Mechanical Turk (AMT) requester to exclude workers that have already performed another similar HIT. This is useful for behavioral and social scientists who need to ensure that their subjects have no prior experience with their task and have no knowledge of the hypotheses being tested. If you have other complex requirements regarding who participates in your studies, we recommend either the built-in qualification system in AMT or Turkit (http://groups.csail.mit.edu/uid/turkit/).

  The page is intended to block access in three situations:
  	1. Previews - No previewing of the survey is allowed, so that workers have no reason to start the survey, leave it and start over again.
	2. Multiple HITs in a group - Often an experiment belongs to a set of similar experiments and you don't want participants in one experiment to also participate in others. By giving them the same group name, this page will prevent that. It also has the added benefit of enabling the researcher to run a single study across multiple HITs without worrying about repeat participants.
	3. Re-entry - If a worker leaves a survey in the middle, this page doesn't allow them to return to the survey through the HIT. The reasoning here is the same as for previewing. Note that they probably have the survey URL in their history and could bypass this page.
	
	This page can be used in conjunction with AMT in two different ways, by embedding it as an external HIT or by creating a regular HIT with javascript code that links to this page and sends the right parameters. The code bundle with this page should include a template for the javascript that does this. It should also include a template for all that is needed to create an external HIT using the command line tools (http://aws.amazon.com/developertools/694).
	
	Finally, this page also prepares cookies used by the completion code page (also included) when generating completion codes.
*/

session_start();

// include the constants from the config file
if (!include('turkGateConfig.php'))
{
	die("A configuration error occurred. Please report this error to the HIT requester.");
}

$groupName=urldecode($_GET["group"]);

// parse survey identifier, first word is a code for finding the base URL, second word is a survey-specific id
$surveyUrl=urldecode($_GET["survey"]);

// We use assignmentId to identify whether the worker is merely previewing the HIT or has accepted it. 
if ($_GET["assignmentId"] == "ASSIGNMENT_ID_NOT_AVAILABLE" || empty($_GET["assignmentId"])) 
{
	// Block previews of the HIT, but warn about possible exclusion based on group name
	
	echo "<p>Sorry, this study cannot be previewed. Please accept the HIT in order to view it.</p>";
	echo "<p>You won't be able to complete the HIT if you have already done other HITs related to $groupName. If this is the case, do not accept the HIT.</p>";
	echo "<p>If you have disabled browser cookies or if you restart your browser after accepting the HIT, it might not work properly. Be sure to have any plug-ins required by the HIT installed before accepting it.</p>";
}
else
{
	// This worker has accepted the HIT, so we can continue
	
	$workerId=htmlspecialchars($_GET["workerId"]);
	$assignId=htmlspecialchars($_GET["assignmentId"]);
		
	// This is the form for submitting the completion codes and comments for an external HIT. 
	// Not needed if this page was reached through a link from a regular HIT.
	$htmlForm = "<form id='mturk_form' method='POST' action='http://www.mturk.com/mturk/externalSubmit'>" . "<input type='hidden' id='assignmentId' name='assignmentId' value='$assignId'>";
	$htmlCompletionCode = '<p>Completion code: <input type="text" name="completion_code" id="completion_code" size="60"></p>';
	$htmlSubmitButton = '<input id="submit_button" type="submit" name="submit_button" value="Submit">';
	$htmlComments = '<p>Comments (optional): <br><textarea cols="60" rows="4" id="comments" name="comments"></textarea></p>';

	// parameters for accessing the database
	$db_username=Constants::DATABASE_USERNAME;
	$db_password=Constants::DATABASE_PASSWORD;
	$db_name=Constants::DATABASE_NAME;
	$db_host=Constants::DATABASE_HOST;

	// connect to the database
	$con = mysql_connect($db_host,$db_username,$db_password);
	if (!$con)
  	{
  		die("There was an error connecting to the database. Please contact the requester to notify them of the error.");
  	}
	mysql_select_db($db_name) or die("There was an error selecting the database. Please contact the requester to notify them of the error.");

	// Look for entries with the same workerId and groupName. 
	$query = "SELECT * FROM SurveyRequest WHERE SurveyRequest.workerId='$workerId' AND SurveyRequest.groupName='$groupName';";
	$result = mysql_query($query) or die("There was an error retreiving access info. Please contact the requester to notify them of the error.");

	//If one exists, this worker has already done a survey in the group and will be blocked from reaching the survey.
	// On the chance that they completed the survey but then closed the browser, we still give them the option to enter a completion code.
	if (mysql_numrows($result) > 0)
	{
		echo '<html><body>';
		// If this page was reached from a link and the HIT page is accessible in another window
		if ($_GET["source"] == "js")
		{
			echo "If you completed the survey for this HIT, enter the completion code in the HIT page on Mechanical Turk.<br>";
		}
		else
		{
			// If this is an external hit, the submission form and explanation for being blocked are presented in the HIT page
			echo $htmlForm;
			echo "If you accidentally left this page after accepting the HIT, but have completed the survey you can enter the completion code here.";
			echo $htmlCompletionCode;
			echo $htmlComments;
			echo $htmlSubmitButton;
			echo '</form>';
		}
			
		echo "Otherwise, you may have already completed another survey in this group and cannot complete this one. Please return the HIT.<br>";
		echo '</body></html>';
	}
	else
	{
		// ACCESS GRANTED
		
		// save the worker ID for later creating the completion code
		$_SESSION['Worker_ID'] = $workerId;
		$_SESSION['Group_Name'] = $groupName;
		setcookie('Worker_ID', $workerId, time() + (24 * 60 * 60), '/');
		setcookie('Group_Name', $groupName, time() + (24 * 60 * 60), '/');
	
		// Add the access to the database to block future access
		$query = "INSERT INTO SurveyRequest (SurveyRequest.workerId, SurveyRequest.groupName, SurveyRequest.URL, SurveyRequest.time) VALUES ('$workerId', '$groupName', '$surveyId', now());";
		$result=mysql_query($query) or die("There was an error saving to the database. Please contact the requester to notify them of the error.");
		
		if ($_GET["source"] == "js")
		{
			// if coming from javascript, simply redirect because the HIT response fields are on another page
			header( 'Location: ' . $surveyUrl );
		}
		else
		{
			// if an external HIT, present a link and the form for submitting the completion code and comments
			echo '<html><body>';
			
			echo '<p>The link below will open the study in a new window.</p>';
			echo '<p>At the end of the survey you will receive a confirmation code. Submit that confirmation code in the text entry box below in order to recieve credit for this HIT.</p><p>Thank you.</p>';
			echo '<p><a name="surveyLink" id="surveyLink" target="_blank" href="' . $surveyUrl . '">Click here to open the study.</a></p>&nbsp;';
			
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
?>