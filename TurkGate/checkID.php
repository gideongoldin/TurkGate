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

$workerId = null;
if (!empty($_POST['checkid_id'])) {	
	$workerId = htmlspecialchars($_POST['checkid_id']);
}

$groupName = urldecode($_GET['group']);
	
if ($workerId == null) { ?>
  <p>
  	You will only be able to view the survey if you have not accepted  
    any other HITs related in the '<?php echo $groupName; ?>' group.
  </p>
  <p>Enter your Amazon Mechanical Turk Worker ID to test whether you have done other HITs in the group.</p>
  <p>
  	<form id='checkid_form' method='POST' action='checkID.php?group=<?php echo urlencode($groupName); ?>'> 
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
	$accessController = new DatabaseObject();
    $accessAllowed = $accessController->checkAccess($workerId, $groupName, false);

    // Block previews of the HIT, but warn about possible exclusion based on
    // group name
	if ($accessAllowed) {
        echo "<p>You have not done any surveys in the '$groupName' group.</p>";
        echo '<p>Once you accept the HIT, you will be able to access the survey.</p>';
	} else {
        echo "<p>You have already accessed a survey in the '$groupName' group.</p>";
        echo '<p>Do NOT accept the HIT, because you will NOT be able to access the survey.</p>';
	}

	$accessController->close();
}

?>