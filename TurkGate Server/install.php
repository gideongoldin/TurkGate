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

<!DOCTYPE html>
<html>
	<head>
		<title>TurkGate</title>
	</head>

	<?php
	// Commonly used variables
	$footer = "<h5>&copy; 2012 <a href='https://github.com/gideongoldin/TurkGate' target='blank'>TurkGate</a></h5>";

	// Check for form submissions
	if (isset($_POST['installSubmit']) || isset($_POST['uninstallSubmit'])) {
		
		// Gather the user-submitted database credentials
		$databaseHost = isset($_POST['databaseHost']) ? $_POST['databaseHost'] : "";
		$databaseName = isset($_POST['databaseName']) ? $_POST['databaseName'] : "";
		$databaseUsername = isset($_POST['databaseUsername']) ? $_POST['databaseUsername'] : "";
		$databasePassword = isset($_POST['databasePassword']) ? $_POST['databasePassword'] : "";
		
		// Validate entries
		if (empty($databaseHost) || empty($databaseName) || empty($databaseUsername) || empty($databasePassword)) {
			// Simple form validation for HTMLn where n < 5
			echo "<h1>TurkGate</h1><h2>Installation</h2><p>Error: All fields are required. Please <a href='javascript:history.back()'>go back</a> and re-submit.</p>" . $footer;
		} else {
			
			// Connect to the database
			$connection = mysql_connect($databaseHost, $databaseUsername, $databasePassword) or die("<h1>TurkGate</h1><h2>Installation</h2><p>Error connecting to database. " . mysql_error() . ". See your system administrator.</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);

			// Select the database
			mysql_select_db($databaseName, $connection) or die("<h1>TurkGate</h1><h2>Installation</h2><p>Error selecting database: " . mysql_error() . "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);
			
			// Check if the uninstall (vs. install) form was submitted
			if(isset($_POST['uninstallSubmit'])) {
				
					echo '<h1>TurkGate</h1><h2>Uninstallation</h2>';
					
					// Remove the table 'SurveyRequest'
					$query = "DROP TABLE $databaseName.SurveyRequest";
					$result = mysql_query($query, $connection);
					if(!$result) {
						echo '<p>TurkGate could not find the SurveyRequest table. Might it have already been removed?</p>';
					} else {
						echo '<p>TurkGate removed the SurveyRequest table.</p>';
					}

					// Close the database connection
					mysql_close($connection);

					// Remove the TurkGate configuration file
					$configFileName = "turkGateConfig.php";
					$result = unlink($configFileName);
					if(!$result) {
						echo '<p>TurkGate did not remove the file turkGateConfig.php. Might it have already been removed?</p><p>Click <a href="index.php">here</a> to go to the main TurkGate page.</p>';
					} else {
						echo '<p>TurkGate removed the file turkGateConfig.php.</p><p>Click <a href="index.php">here</a> to go to the main TurkGate page.</p>';
					}
					
					echo $footer;
				
			} else {
				// The install (vs. uninstall) form was submitted
				
				// Create the TurkGate configuration file
				$configFileName = "turkGateConfig.php";
				$configFileHandle = fopen($configFileName, 'w') or die("<h1>TurkGate</h1><h2>Installation</h2><p>Error creating config file. " . mysql_error() . ".</p>" . $footer);

				// Write (to) the TurkGate configuration file
				$configFileString = "<?php
			define('DATABASE_HOST', '" . $databaseHost . "');
			define('DATABASE_NAME', '" . $databaseName . "');
			define('DATABASE_USERNAME', '" . $databaseUsername . "');
			define('DATABASE_PASSWORD', '" . $databasePassword . "');
			?>";
				fwrite($configFileHandle, $configFileString);
				fclose($configFileHandle);

				// Create the table
				$sql = "CREATE TABLE IF NOT EXISTS SurveyRequest 
			(
				requestID INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY(requestID),
				workerID VARCHAR(256),
				URL VARCHAR(256),
				groupName VARCHAR(256),
				time DATETIME
			)";

				mysql_query($sql, $connection) or die("<p>Error creating table: " . mysql_error() . "</p><p><a href='javascript:history.back()'>Go back</a></p>" . $footer);
				mysql_close($connection);

				// Installation is now complete
				echo '<h1>TurkGate</h1><h2>Installation</h2><p>TurkGate Installation successful!</p>';
				echo '<p>Click <a href="index.php">here</a> to go to the main TurkGate page.</p>' . $footer;
				exit();
			}
		}
		exit();
	}
?>

	<body>
		<h1>TurkGate</h1>
		<h2>To install...</h2>

		<p>
			Please enter your database (e.g., MySQL) login information below.
		</p>
		<p>
			Installing TurkGate will automatically add a table ('SurveyRequest') to the database specified below.
		</p>

		<form method="post" action="install.php">

			<p>
				<label for="databaseHost">Database Host:</label>
				<input type="text" name="databaseHost" value="localhost" required="required">
				<i>(This value is usually 'localhost')</i>
			</p>
			
			<p>
				<label for="databaseName">Database Name:</label>
				<input type="text" name="databaseName" required="required" autofocus="autofocus">
				<i>(E.g., 'TurkGate')</i>
			</p>

			<p>
				<label for="databaseUsername">Database Username:</label>
				<input type="text" name="databaseUsername" required="required">
			</p>

			<p>
				<label for="databasePassword">Database Password:</label>
				<input type="password" name="databasePassword" required="required">
			</p>
		
			<p>
				<input type="submit" name="installSubmit" value="Install TurkGate">
			</p>

		</form>
		
		<hr />
		
			<h2>To uninstall...</h2>

			<p>
				Please enter your database (e.g., MySQL) login information below.
			</p>
			<p>
				Uninstalling TurkGate will remove all TurkGate-generated files and tables. It will not remove TurkGate source files.
			</p>

			<form method="post" action="install.php" onsubmit="if(!confirm('Are you sure you want to uninstall TurkGate?')) { return false; }">

				<p>
					<label for="databaseHost">Database Host:</label>
					<input type="text" name="databaseHost" value="localhost" required="required">
					<i>(This value is usually 'localhost')</i>
				</p>

				<p>
					<label for="databaseName">Database Name:</label>
					<input type="text" name="databaseName" required="required" autofocus="autofocus">
					<i>(E.g., 'TurkGate')</i>
				</p>

				<p>
					<label for="databaseUsername">Database Username:</label>
					<input type="text" name="databaseUsername" required="required">
				</p>

				<p>
					<label for="databasePassword">Database Password:</label>
					<input type="password" name="databasePassword" required="required">
				</p>

				<p>
					<input type="submit" name="uninstallSubmit" value="Uninstall TurkGate">
				</p>

			</form>

		<?php echo $footer ?>

	</body>
</html>