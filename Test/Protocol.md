# TESTING PROTOCOL

1. Uninstall old version
	1. Verify config file and database table are removed
2. Download new version
3. Navigate to admin page. 
	1. Verify message that TurkGate is not installed.
3. Install new version
	1. Verify config file and database table are added
4. Return to admin page.
	1. Verify that TurkGate is installed.
5. Test Preview HIT with random ID (make sure to copy this), test group, and test URL for Web Interface HIT.
	1.  Verify that you get appropriate message: "You have not done any surveys in the test group group. Once you accept the HIT, you will be able to access the survey.".
	2. Go back, and try again with same information. Verify that you get the same message as above.
	3. Go back, and Test Accept HIT with the same information. Verify that you are let through to the testDestination.php page.
	4. Go back, and Test Accept HIT again with the same information. Verify that you get the message: "If you completed the survey for this HIT, enter the completion code in the HIT page on Mechanical Turk. Otherwise, you may have already completed another survey in this group and cannot complete this one. Please return the HIT."
	5. Go back, and Test Preview HIT again with the same information. Verify that you get: "You have already accessed a survey in the test group group. Do NOT accept the HIT, because you will NOT be able to access the survey."
7. Repeat all of step (5) above using the Command Line Tools HIT and a new random ID, as shown below:
	1.  Verify that you get appropriate message: "You have not done any surveys in the test group group. Once you accept the HIT, you will be able to access the survey.".
	2. Go back, and try again with same information. Verify that you get the same message as above.
	3. Go back, and Test Accept HIT with the same information. Verify that you are provided a link to open the study, and fields for completion code and comments, and a Submit button. Verify that link opens testDestination.php in a new tab.
	4. Go back, and Test Accept HIT again with the same information. Verify that you don't get a link to open the study, but do get a box for completion code and comments, as well as a Submit button.
	5. Go back, and Test Preview HIT again with the same information. Verify that you get: "You have already accessed a survey in the test group group. Do NOT accept the HIT, because you will NOT be able to access the survey."

... to be continued!