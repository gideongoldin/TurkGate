# TESTING PROTOCOL

## Test compatibility and download new version
1. Test Accept HIT with random ID and save ID and group name for later
2. Download ZIP from https://github.com/gideongoldin/TurkGate (not from TurkGate download page)
3. Overwrite files on server with files from ZIP. DON'T delete files that aren't in ZIP, including config.php
4. Test Accept HIT again with same ID and group name. Verify that access is denied.
5. Test Accept HIT with new random ID. Verify that access is given.


## Clean install of new version
1. Uninstall old version
	1. Verify config file and database table are removed
2. Navigate to admin page. 
	1. Verify message that TurkGate is not installed.
3. Install new version
	1. Verify config file and database table are added
4. Return to admin page.
	1. Verify that TurkGate is installed.
	
## Test access control
1. Test Preview HIT with random ID (make sure to copy this), test group, and test URL for Web Interface HIT.
	1. Verify that you get a message stating that you cannot preview the study and a link to check eligibility. Click the link.
	2. Paste worker ID and click button. Verify message that have not done any surveys.
	2. Go back to admin page, and test preview again with same information. Verify that you get the same message as above.
	3. Go back, and Test again without preview selected. Verify that you get a link to the study and that it takes you to testDestination.php page.
	4. Go back, and Test again with the same information. Verify that you get a link and that it takes you to a message that you may have already completed the study.
	5. Go back, and Test Preview HIT again with the same information. Verify that you get a link to check eligibility and that checking the worker ID tells you that you can't access the study.
2. Repeat all of step (1) above using the Command Line Tools HIT and a new random ID, with the following differences:
	1. When accepting the HIT the second time (4), verify that there is no link to the study.
	
## Test completion codes
1. Test Accept HIT with a new random ID. Save the ID and group name to compare. Click the link to go to the study.
2. Enter a name and value and click Go To Completion Code.
3. Verify completion code contains correct ID, group name and name/value pair.
4. Copy completion code
5. Future plans: Test completion code verification here.

## Test HIT Creation
1. Create a HIT in survey site (e.g. Qualtrics), and copy link to survey.
2. On TurkGate Generate a HIT page, choose Survey Site, paste survey link and create a group name. 
3. Test Web Interface HIT
	1. Choose HIT Type Web Interface and click Generate HIT
	2. Copy completion code URL and set as exit URL in survey.
	3. Create a HIT in Mechanical Turk sandbox using web interface (Don't forget to allow non-Masters).
	4. Copy HTML from TurkGate page to Mechanical Turk Edit HTML Source window. HTML should automatically be selected when clicking with textbox.
	5. Publish HIT.
	6. Repeat steps 1,3,4,5 to create a second HIT with the same group name. Edit the project, because simply clicking New Batch will add assignments to the same HIT.
	7. In worker sandbox, open one of the HITs.
	8. Click "check eligibility". Verify that you are eligible.
	9. Accept the HIT and click to open the study.
	10. Complete the study and copy the completion code. Completion code should automatically be selected when clicking anywhere on it.
	11. Submit the HIT with the copied completion code.
	12. Open the other HIT.
	13. Click "check eligibility". Verify that you are NOT eligible.
	14. Accept the HIT and click the link to the study. Verify that you are blocked from reaching the study.
4. Test Command Line Tools HIT
	1. Create a new CLT survey folder per instructions in the wiki.
	2. Go to Generate a HIT page. Fill in survey URL and create a new group name.
	3. Choose HIT Type Command Line Tools and generate the HIT. Verify that you get three files to download and a completion code.
	4. Download the files and put them in the new survey folder, replacing existing files.
	5. Change the survey title in survey.properties to a new title.
	6. Run the run script to create the HIT. If your CLT doesn't use the sandbox by default, make sure to add the -sandbox parameter.
	7. Change the survey title again to a new title and run the run script again to create a second HIT with the same group name.
	8. Find the first HIT and open it. Verify that the study can't be previewed and that there is a link to check eligibility.
	9. Repeat steps 3.8-3.11
	10. Open the other HIT.
	11. Check eligibility and verify that you are not eligible.
	12. Accept the HIT and verify there is no link to the survey.

## Test verifying completion codes
	1. Download results for first Web HIT batch.
	2. Future issue: Add various manipulations of the existing completion code.
	3. Copy contents into Verify page.
	4. Make sure correct completion code gets valid result (unless you've added duplicates) and all others get invalid results.

	
	
	
	
	
	